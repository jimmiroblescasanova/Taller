<?php 

namespace App\Filament\Resources\EstimateResource;

use Filament\Forms;
use App\Models\Contact;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\ContactResource;

class EstimateForm extends Form
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    static::getEstimateHeading(),

                    static::getEstimateItems(),
                ])
                ->columnSpanFull(),

                static::showTotalsArea()
                ->columns(3),
            ]);
    }

    public static function getEstimateHeading(): Step
    {
        return Step::make('Datos generales')
        ->icon('heroicon-o-user-circle')
        ->schema([
            Forms\Components\Select::make('contact_id')
                ->label('Seleccionar contacto')
                ->relationship(name: 'contact', titleAttribute: 'full_name')
                ->searchable()
                ->required()
                ->createOptionForm(fn (Form $form) => ContactResource::form($form)),
            Forms\Components\Select::make('agent_id')
                ->label('Agente de venta')
                ->relationship(
                    name: 'agent', 
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->isActive())
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('title')
                ->label('Título de la cotización')
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public static function getEstimateItems(): Step
    {
        return Step::make('Productos')
        ->icon('heroicon-o-shopping-bag')
        ->schema([
            Forms\Components\Repeater::make('items')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable()
                    ->optionsLimit(25)
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set){
                        return self::searchPrice($get, $set);
                    })
                    ->columnSpan(3)
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cant.')
                    ->numeric()
                    ->step(1)
                    ->columnSpan(1)
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step('0.01')
                    ->prefix('$')
                    ->columnSpan(1)
                    ->required(),
            ])
            ->live(onBlur: true)
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                return self::updateTotals($get, $set);
            })
            ->columns(5),
        ]);
    }

    public static function showTotalsArea(): Forms\Components\Fieldset
    {
        return Forms\Components\Fieldset::make('Totales')
            ->schema([
                Forms\Components\TextInput::make('subtotal')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->readOnly()
                    ->prefix('$'),
                Forms\Components\TextInput::make('tax')
                    ->label('Impuestos')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->readOnly()
                    ->prefix('$'),
                Forms\Components\TextInput::make('total')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->readOnly()
                    ->prefix('$'),
            ]);
    }

    public static function searchPrice(Forms\Get $get, Forms\Set $set): void
    {
        $priceList = Contact::where('id', $get('../../contact_id'))->value('price_list');
        
        $price = Product::where('id', $get('product_id'))->value($priceList ?? 'price_1');

        $set('price', $price);
    }

    public static function updateTotals($get, $set): void
    {
        $selectedProducts = collect($get('items'))->filter(function ($item){
            return !empty($item['product_id']) && !empty($item['quantity']) && !empty($item['price']);
        });

        $subtotal = $selectedProducts->reduce(function ($sum, $product) {
            return $sum + ($product['quantity'] * $product['price']);
        }, 0);

        $set('subtotal', number_format($subtotal, 2));
        $set('tax', number_format(($subtotal * 1.16) - $subtotal, 2));
        $set('total', number_format(($subtotal * 1.16), 2));
    }
}
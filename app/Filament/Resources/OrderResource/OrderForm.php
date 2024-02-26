<?php 

namespace App\Filament\Resources\OrderResource;

use Filament\Forms;
use App\Models\Vehicle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Wizard;

class OrderForm extends Form
{
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Wizard::make([
                static::getHeaderStep(),
                static::getItemsStep(),
                static::getAgentStep(),
            ])->columnSpanFull(),

            static::getTotalsSection()->columns(3),
        ]);
    }

    public static function getHeaderStep(): Wizard\Step
    {
        return Wizard\Step::make('Orden')
        ->schema([
            Forms\Components\TextInput::make('title')
                ->label('Título')
                ->required(),
            Forms\Components\Select::make('contact_id')
                ->label('Seleccionar contacto')
                ->relationship(name: 'contact', titleAttribute: 'full_name')
                ->optionsLimit(15)
                ->searchable()
                ->selectablePlaceholder(false)
                ->live()
                ->required(),
            Forms\Components\Select::make('vehicle_id')
                ->label('Selecciona una unidad')
                ->options(fn (Get $get): Collection => Vehicle::query()
                    ->where('contact_id', $get('contact_id'))
                    ->pluck('model', 'id'))
                ->searchable()
                ->preload()
                ->selectablePlaceholder(false)
                ->required(),
        ]);
    }

    public static function getItemsStep(): Wizard\Step
    {
        return Wizard\Step::make('Productos')
        ->schema([
            Forms\Components\Repeater::make('items')
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Buscar producto')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable()
                    ->optionsLimit(25)
                    ->required()
                    ->columnSpan(3),
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->integer()
                    ->step(1)
                    ->prefix('#')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->numeric()
                    ->inputMode('decimal')
                    ->step('0.01')
                    ->prefix('$')
                    ->required(),
            ])
            ->reorderable(false)
            ->live(debounce: 1000)
            ->afterStateUpdated( function (Get $get, Set $set) {
                self::updateTotals($get, $set);
            })
            ->columns(5),
        ]);
    }

    public static function getAgentStep(): Wizard\Step
    {
        return Wizard\Step::make('Agentes')
        ->schema([
            Forms\Components\Select::make('agent_id')
                ->label('Agente de venta')
                ->relationship(name: 'agent', titleAttribute: 'name')
                ->selectablePlaceholder(false)
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('specialist_id')
                ->label('Especialista asignado')
                ->relationship(name: 'specialist', titleAttribute: 'name')
                ->selectablePlaceholder(false)
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\Select::make('station_id')
                ->label('Estacion de trabajo')
                ->relationship(name: 'station', titleAttribute: 'name')
                ->selectablePlaceholder(false)
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }

    public static function getTotalsSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make()
        ->schema([
            Forms\Components\Textinput::make('subtotal')
                ->label('Subtotal')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly()
                ->prefix('$'),
            Forms\Components\Textinput::make('tax')
                ->label('Impuestos')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly()
                ->prefix('$'),
            Forms\Components\Textinput::make('total')
                ->label('TOTAL')
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric()
                ->readOnly()
                ->prefix('$'),
        ]);
    }

    public static function updateTotals(Get $get, Set $set): void
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
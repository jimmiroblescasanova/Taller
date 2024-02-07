<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Estimate;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\EstimateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EstimateResource\RelationManagers;

class EstimateResource extends Resource
{
    protected static ?string $model = Estimate::class;

    protected static ?string $modelLabel = 'cotización';

    protected static ?string $pluralModelLabel = 'cotizaciones';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    self::stepHeading(),

                    self::stepItems(),
                ])
                ->columnSpanFull(),

                self::subtotals()
                ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
                Tables\Columns\TextColumn::make('contact.full_name')
                    ->label('Contacto')
                    ->description(fn (Estimate $record): string => $record->title),
                Tables\Columns\TextColumn::make('total')
                    ->numeric(2, '.', ',')
                    ->prefix('$')
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstimates::route('/'),
            'create' => Pages\CreateEstimate::route('/create'),
            'view' => Pages\ViewEstimate::route('/{record}'),
            'edit' => Pages\EditEstimate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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

    public static function stepHeading(): Step
    {
        return Step::make('Cotización')
        ->schema([
            Forms\Components\Select::make('contact_id')
                ->label('Seleccionar contacto')
                ->relationship(name: 'contact', titleAttribute: 'full_name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('agent_id')
                ->label('Agente de venta')
                ->relationship(name: 'agent', titleAttribute: 'name')
                ->searchable()
                ->preload()
                ->required(),
            Forms\Components\TextInput::make('title')
                ->label('Título de la cotización')
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public static function stepItems(): Step
    {
        return Step::make('Productos')
        ->schema([
            Forms\Components\Repeater::make('items')
            ->relationship()
            ->schema([
                Forms\Components\TextInput::make('quantity')
                    ->label('Cant.')
                    ->numeric()
                    ->default(1)
                    ->step(1)
                    ->columnSpan(1),
                Forms\Components\Select::make('product_id')
                    ->label('Producto')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable()
                    ->optionsLimit(25)
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set){
                        self::searchPrice($get, $set);
                    })
                    ->columnSpan(3),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->step('0.01')
                    ->prefix('$')
                    ->columnSpan(1),
            ])
            ->live(debounce: 1000)
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                return self::updateTotals($get, $set);
            })
            ->columns(5),
        ]);
    }

    public static function subtotals(): Forms\Components\Fieldset
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
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Vehicle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    
    protected static ?string $modelLabel = 'orden';
    protected static ?string $pluralModelLabel = 'ordenes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Orden')
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
                    ]),
                    Forms\Components\Wizard\Step::make('Productos')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Buscar producto')
                                ->relationship(name: 'product', titleAttribute: 'name')
                                ->searchable()
                                ->optionsLimit(25)
                                ->required(),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Cantidad')
                                ->integer()
                                ->default(1)
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
                        ->columns(3),
                    ]),
                    Forms\Components\Wizard\Step::make('Agentes')
                    ->schema([
                        Forms\Components\Select::make('agent_id')
                            ->label('Agente de venta')
                            ->relationship(name: 'agent', titleAttribute: 'name')
                            ->searchable()
                            ->optionsLimit(10)
                            ->selectablePlaceholder(false)
                            ->required(),
                        Forms\Components\Select::make('specialist_id')
                            ->label('Especialista asignado')
                            ->relationship(name: 'specialist', titleAttribute: 'name')
                            ->searchable()
                            ->optionsLimit(10)
                            ->selectablePlaceholder(false)
                            ->required(),
                        Forms\Components\Select::make('station_id')
                            ->label('Estacion de trabajo')
                            ->relationship(name: 'station', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                ])->columnSpanFull(),
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Textinput::make('subtotal')
                        ->label('Subtotal')
                        ->readOnly()
                        ->prefix('$')
                        ->afterStateHydrated(function (Get $get, Set $set) {
                            self::updateTotals($get, $set);
                        })
                        ->dehydrated(false),
                    Forms\Components\Textinput::make('tax')
                        ->label('Impuestos')
                        ->readOnly()
                        ->prefix('$')
                        ->afterStateHydrated(function (Get $get, Set $set) {
                            self::updateTotals($get, $set);
                        })
                        ->dehydrated(false),
                    Forms\Components\Textinput::make('total')
                        ->label('TOTAL')
                        ->readOnly()
                        ->prefix('$')
                        ->afterStateHydrated(function (Get $get, Set $set) {
                            self::updateTotals($get, $set);
                        })
                        ->dehydrated(false),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('contact.full_name')
                    ->description(fn (Order $record): string => $record->title),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->date('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('inventory')
                ->action(function (Order $record) {
                    $inventory = $record->inventory()->create([
                        'vehicle_id' => $record->vehicle_id,
                    ]);

                    return redirect()->route('filament.admin.resources.vehicle-inventories.edit', $inventory);
                })
                ->hidden(fn(Order $record): bool => $record->inventory()->exists() 
                || $record->status == OrderStatusEnum::INCOMPLETE),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
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

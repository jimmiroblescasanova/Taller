<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Estimate;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use App\Enums\EstimateEstatusEnum;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
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
                Tables\Columns\TextColumn::make('agent.name'),
                Tables\Columns\TextColumn::make('total')
                    ->prefix('$')
                    ->numeric(2)
                    ->alignEnd()
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total')
                            ->formatStateUsing(fn (string $state) => Number::currency($state / 100))
                    ),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('transform')
                        ->label('Transformar')
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->requiresConfirmation()
                        ->action(fn (Estimate $record) => self::transformEstimate($record))
                        ->hidden(fn (Estimate $record) => $record->status === EstimateEstatusEnum::ORDERED),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
                ->link()
                ->label('Opciones'),
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

    public static function transformEstimate(Estimate $record)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::create([
                'title' => $record->title,
                'contact_id' => $record->contact_id,
                'agent_id' => $record->agent_id,
                'subtotal' => $record->subtotal,
                'tax' => $record->tax,
                'total' => $record->total,
                'estimate_id' => $record->id,
                'status' => OrderStatusEnum::INCOMPLETE
            ]);
    
            foreach ($record->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $record->update([
                'status' => 1,
                'order_id' => $order->id,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Notification::make()
                ->title('Ocurrio un error')
                ->danger()
                ->send();

            return false;
        }

        return redirect()->route('filament.admin.resources.orders.edit', $order);
    }
}

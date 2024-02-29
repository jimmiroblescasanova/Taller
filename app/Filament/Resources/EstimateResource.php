<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Order;
use App\Models\Estimate;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Number;
use Filament\Resources\Resource;
use App\Enums\EstimateStatusEnum;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EstimateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EstimateResource\EstimateForm;
use App\Filament\Resources\EstimateResource\RelationManagers;

class EstimateResource extends Resource
{
    protected static ?string $model = Estimate::class;

    protected static ?string $modelLabel = 'cotización';

    protected static ?string $pluralModelLabel = 'cotizaciones';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return EstimateForm::form($form);
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
                    ->label('Contacto / Título')
                    ->description(fn (Estimate $record): string => $record->title)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agente'),
                Tables\Columns\TextColumn::make('total')
                    ->prefix('$')
                    ->numeric(2)
                    ->alignEnd()
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label('Total')
                            ->formatStateUsing(fn (string $state) => Number::currency($state / 100))
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('agent')
                    ->label('Agente')
                    ->multiple()
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('transform')
                        ->label('Transformar')
                        ->icon('heroicon-o-arrow-path-rounded-square')
                        ->requiresConfirmation()
                        ->action(fn (Estimate $record) => self::transformEstimate($record))
                        ->hidden(fn (Estimate $record) => $record->status === EstimateStatusEnum::ORDERED),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn (Estimate $record) => $record->order()->exists()),
                ])
                ->link()
                ->label('Opciones')
                ->hidden(fn (Estimate $record) => $record->trashed()),
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
                'status' => EstimateStatusEnum::ORDERED,
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

        Notification::make()
            ->title('Orden creada')
            ->success()
            ->send();

        return redirect()->route('filament.admin.resources.orders.edit', $order);
    }
}

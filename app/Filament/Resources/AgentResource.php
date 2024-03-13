<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Filters;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AgentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentResource\RelationManagers;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $modelLabel = 'agente';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Empresa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre completo')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('comision')
                    ->label('Comision de venta')
                    ->suffix('%')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->inline(false)
                    ->visibleOn('edit'),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre completo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('comision')
                    ->label('Comision de venta')
                    ->suffix('%')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha alta')
                    ->sortable()
                    ->date()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('active')
                    ->label('Estado')
                    ->badge()
                    ->alignCenter(),
            ])
            ->filters([
                Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('active', true))
                    ->label('Activos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAgents::route('/'),
        ];
    }    
}

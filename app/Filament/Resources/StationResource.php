<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Station;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StationResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StationResource\RelationManagers;

class StationResource extends Resource
{
    protected static ?string $model = Station::class;

    protected static ?string $modelLabel = 'estación';

    protected static ?string $pluralModelLabel = 'estaciones';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Empresa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Estación de trabajo')    
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('name', 'ASC'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Estación de trabajo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->date()
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Últ. actualización')
                    ->since()
                    ->sortable()
                    ->alignEnd(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStations::route('/'),
        ];
    }    
}

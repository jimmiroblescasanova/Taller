<?php

namespace App\Filament\Resources;

use App\Enums\SpecialistType;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Specialist;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpecialistResource\Pages;
use App\Filament\Resources\SpecialistResource\RelationManagers;

class SpecialistResource extends Resource
{
    protected static ?string $model = Specialist::class;

    protected static ?string $modelLabel = 'especialista';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Empresa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre completo')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo de especialista')
                    ->options(SpecialistType::class)
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('active')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de alta')    
                    ->date()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ult. modificación')
                    ->since()
                    ->alignEnd(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpecialists::route('/'),
        ];
    }    
}

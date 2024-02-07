<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ContactMedia;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactResource\RelationManagers;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $modelLabel = 'contacto';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catalogos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->description('Información general')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre (s)')
                        ->required(),
                    Forms\Components\TextInput::make('lastname')
                        ->label('Apellido (s)')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel(),
                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email(),
                ])
                ->columns(2),
                Forms\Components\Section::make()
                ->description('Informacion adicional')
                ->schema([
                    Forms\Components\Select::make('channel')
                        ->label('Como nos conocio')
                        ->options(ContactMedia::class),
                    Forms\Components\Select::make('price_list')
                        ->label('Lista de Precios')
                        ->options(function() {
                            $arr = [];
                            for ($i=1; $i <= 10; $i++) { 
                                $arr['price_' . $i] = 'Precio ' . $i;
                            }
                            return $arr;
                        }),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas')
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nombre completo'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de alta')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ClientsRelationManager::class,
            RelationManagers\VehiclesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }    
}

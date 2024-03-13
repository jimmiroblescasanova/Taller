<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\ContactMedia;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ContactResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ContactResource\ContactInfolist;
use App\Filament\Resources\ContactResource\RelationManagers;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $modelLabel = 'contacto';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Catalogos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información general')
                ->icon('heroicon-o-identification')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre (s)')
                        ->required(),
                    Forms\Components\TextInput::make('lastname')
                        ->label('Apellido (s)')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->mask('999-999-9999')
                        ->placeholder('999-999-9999'),
                    Forms\Components\TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email(),
                ])
                ->columns(2),

                Forms\Components\Section::make('Informacion adicional')
                ->icon('heroicon-o-chat-bubble-bottom-center-text')
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return ContactInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->modifyQueryUsing(function (Builder $query) {
                $query->orderBy('full_name', 'ASC');
            })
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nombre completo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo electrónico')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de alta')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\VehiclesRelationManager::class,
            RelationManagers\ClientsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'view' => Pages\ViewContact::route('/{record}'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
        ];
    }    
}

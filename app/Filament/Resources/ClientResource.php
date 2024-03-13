<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;

class ClientResource extends Resource
{
    protected static ?string $modelLabel = 'cliente';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Catalogos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                infolists\Components\Group::make()
                ->schema([
                    Infolists\Components\Section::make('Datos personales')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Razón Social')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('code')->label('Código CONTPAQi'),
                        Infolists\Components\TextEntry::make('rfc')->label('R.F.C.'),
                        Infolists\Components\TextEntry::make('tradebane')->label('Nombre comercial'),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(3),

                    Infolists\Components\Section::make('Datos de envío')
                    ->icon('heroicon-o-at-symbol')
                    ->schema([
                        Infolists\Components\TextEntry::make('email1')->label('Correo electrónico 1'),
                        Infolists\Components\TextEntry::make('email2')->label('Correo electrónico 2'),
                        Infolists\Components\TextEntry::make('email3')->label('Correo electrónico 3'),
                    ])
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),
                ])
                ->columnSpan(2),

                Infolists\Components\Section::make('Modificaciones')
                ->icon('heroicon-o-calendar')
                ->schema([
                    Infolists\Components\TextEntry::make('updated_at')
                        ->label('Ult. actualización')
                        ->since(),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Razón Social')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rfc')
                    ->label('R.F.C.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tradename')
                    ->label('Nombre Comercial')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->recordUrl(
                fn (Model $record): string => Pages\ViewClient::getUrl([$record->id]),
            );
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ContactsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }    
}

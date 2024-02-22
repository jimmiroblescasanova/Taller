<?php

namespace App\Filament\Resources\ContactResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ClientsRelationManager extends RelationManager
{
    protected static string $relationship = 'clients';

    protected static ?string $modelLabel = 'empresa';
    
    protected static ?string $title = 'Empresas';

    protected static ?string $icon = 'heroicon-o-briefcase';

    public function infolist(Infolist $infolist): Infolist
    {
        return ClientResource::infolist($infolist);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Código'),
                Tables\Columns\TextColumn::make('name')->label('Razón Social'),
                Tables\Columns\TextColumn::make('rfc')->label('R.F.C.'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DetachAction::make(),
            ]);
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->clients()->count();

        return $count > 0 ? $count : null;
    }
}

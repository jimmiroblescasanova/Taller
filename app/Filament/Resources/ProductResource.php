<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms\Components\Section;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Empresa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                ->schema([
                    self::generalInfo('Datos generales')
                    ->icon('heroicon-m-bars-3')
                    ->columns(2)
                    ->columnSpan(2),
                    
                    self::clasifications('Clasificaciones')
                    ->icon('heroicon-o-list-bullet')
                    ->columnSpan(1),
                ])
                ->columns(3),

                self::pricesSection('Lista de precios')
                ->icon('heroicon-o-currency-dollar')
                ->columns(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Descripción'),
                Tables\Columns\TextColumn::make('inventory')
                    ->label('Inventario'),
                Tables\Columns\TextColumn::make('price_1')
                    ->label('Precio 1')
                    ->numeric(2)
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    

    public static function generalInfo(string $heading = null): Section
    {
        return Section::make(heading: $heading)
        ->schema([
            Forms\Components\TextInput::make('name')
            ->label('Descripción')    
            ->readOnly()
            ->columnSpanFull(),

            Forms\Components\TextInput::make('code')
            ->label('Código')
            ->readOnly(),

            Forms\Components\TextInput::make('um')
            ->label('Unidad de Medida')
            ->readOnly(),

            Forms\Components\TextInput::make('inventory')
            ->label('Inventario')
            ->suffix('PZA')
            ->readOnly(),

            Forms\Components\Placeholder::make('updated_at')
            ->label('Ult. modificación')
            ->content(fn (Product $record): string => $record->updated_at->diffForHumans()),
        ]);
    }

    public static function clasifications(string $heading = null): Section
    {
        $section = Section::make(heading: $heading);
        $clasifications = [];

        for($i = 1; $i <= 3; $i++) {
            $clasificationName = 'cl_' . $i;

            $repeater = Forms\Components\TextInput::make($clasificationName)
            ->label('Clasificación ' . $i)
            ->readOnly();

            $clasifications[] = $repeater;
        }
        
        return $section->schema($clasifications);
    } 

    public static function pricesSection(string $heading = null): Section
    {
        $section = Section::make(heading: $heading);
        $textInputs = [];

        for($i = 1; $i <= 10; $i++) {
            $price = 'price_' . $i;

            $repeater = Forms\Components\TextInput::make($price)
            ->label('Precio ' . $i)
            ->numeric()
            ->step('0.01')
            ->prefix('$');

            $textInputs[] = $repeater;
        }

        return $section->schema($textInputs);
    }
}

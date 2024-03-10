<?php 

namespace App\Filament\Resources\ProductResource;

use Filament\Forms;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;

class ProductForm extends Form 
{
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
                ->description('Cualquier cambio realizado, será actualizado en la próxima sincronización.')
                ->icon('heroicon-o-currency-dollar')
                ->columns(5),
            ]);
    }

    public static function generalInfo(string $heading = null): Section
    {
        return Section::make(heading: $heading)
        ->schema([
            TextInput::make('name')
            ->label('Descripción')    
            ->readOnly()
            ->columnSpanFull(),

            TextInput::make('code')
            ->label('Código')
            ->readOnly(),

            TextInput::make('um')
            ->label('Unidad de Medida')
            ->readOnly(),

            TextInput::make('inventory')
            ->label('Inventario')
            ->suffix('PZA')
            ->readOnly(),

            Placeholder::make('updated_at')
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

            $repeater = TextInput::make($clasificationName)
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

            $repeater = TextInput::make($price)
            ->label('Precio ' . $i)
            ->numeric()
            ->step('0.01')
            ->prefix('$');

            $textInputs[] = $repeater;
        }

        return $section->schema($textInputs);
    }
}
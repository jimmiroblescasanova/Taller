<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class SyncProducts extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-down';

    protected static string $view = 'filament.pages.sync-products';

    protected static ?string $navigationGroup = 'CONTPAQi';

    protected static ?string $title = 'Sincronizacion de Productos';

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Placeholder::make('agreement')
                    ->label('Información')
                    ->content('Acepto comenzar la sincronización de todos los productos y servicios. Este proceso puede demorar de acuerdo a la cantidad de registros que existan.'),
                Components\Select::make('type')
                    ->label('Tipo de producto')
                    ->options([
                        1 => 'Producto',
                        3 => 'Servicios'
                    ])
                    ->required(),
                Components\Checkbox::make('accept')
                    ->label('Acepto')
                    ->required()
            ])
            ->statePath('data');
    }

    public function submit()
    {
        // Opcion temporal para aumentar el limite de espera
        ini_set('max_execution_time', 300);

        try {
            DB::connection('sqlcompac')->getPdo();
        } catch (\Throwable $th) {
            Notification::make()
            ->title('Error en la conexion con CONTPAQi')
            ->danger()
            ->send();

            return false;
        }

        if ($this->data['type'] == 1) 
        {
            // Obtener el mes actual
            $mes = date('n');
            // Construir el nombre de la columna dinámica
            $cEntradas = 'EC.CENTRADASPERIODO' . $mes;
            $cSalidas = 'EC.CSALIDASPERIODO' . $mes;

            $products = DB::connection('sqlcompac')
                ->table('admProductos as PR')
                ->join('admExistenciaCosto AS EC', 'PR.CIDPRODUCTO', '=', 'EC.CIDPRODUCTO')
                ->join('admEjercicios AS EJ', 'EC.CIDEJERCICIO', '=', 'EJ.CIDEJERCICIO')
                ->join('admUnidadesMedidaPeso AS UM', 'PR.CIDUNIDADBASE', '=', 'UM.CIDUNIDAD')
                ->select('PR.*', 'UM.CNOMBREUNIDAD', $cEntradas . ' AS ENTRADAS', $cSalidas . ' AS SALIDAS')
                ->where([
                    ['PR.CTIPOPRODUCTO', 1],
                    ['PR.CSTATUSPRODUCTO', 1],
                    ['EC.CIDALMACEN', 1],
                    ['EJ.CEJERCICIO', date('Y')],
                ])
                ->get();
        } else {
            $products = DB::connection('sqlcompac')
                ->table('admProductos as PR')
                ->join('admUnidadesMedidaPeso AS UM', 'PR.CIDUNIDADBASE', '=', 'UM.CIDUNIDAD')
                ->select('PR.*', 'UM.CNOMBREUNIDAD')
                ->where([
                    ['PR.CTIPOPRODUCTO', 3],
                    ['PR.CSTATUSPRODUCTO', 1],
                ])
                ->get();
        }

        $clasifications = DB::connection('sqlcompac')->table('admClasificacionesValores')->pluck('CIDVALORCLASIFICACION', 'CVALORCLASIFICACION');

        foreach ($products as $product) {
            Product::updateOrCreate([
                'code' => $product->CCODIGOPRODUCTO
            ], [
                'name' => $product->CNOMBREPRODUCTO,
                'type' => $product->CTIPOPRODUCTO,
                'status' => $product->CSTATUSPRODUCTO,
                'um' => $product->CNOMBREUNIDAD,
                'inventory' => $this->data['type'] === 1 ? ($product->ENTRADAS - $product->SALIDAS) : 0,
                'cl_1' => trim($clasifications->search($product->CIDVALORCLASIFICACION1)),
                'cl_2' => trim($clasifications->search($product->CIDVALORCLASIFICACION2)),
                'cl_3' => trim($clasifications->search($product->CIDVALORCLASIFICACION3)),
                'price_1' => $product->CPRECIO1,
                'price_2' => $product->CPRECIO2,
                'price_3' => $product->CPRECIO3,
                'price_4' => $product->CPRECIO4,
                'price_5' => $product->CPRECIO5,
                'price_6' => $product->CPRECIO6,
                'price_7' => $product->CPRECIO7,
                'price_8' => $product->CPRECIO8,
                'price_9' => $product->CPRECIO9,
                'price_10' => $product->CPRECIO10,
            ]);
        }

        Notification::make()
            ->title('Productos sincronizados')
            ->success()
            ->send();

        return redirect()->route('filament.admin.pages.dashboard');
    }

    public static function canAccess(): bool
    {
        $user = User::find(auth()->user()->id);
        
        return $user->isSuperAdmin() || $user->checkPermissionTo('sincronizar contpaqi');
    }
}

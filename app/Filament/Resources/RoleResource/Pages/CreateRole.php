<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\RoleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            DB::beginTransaction();
            // Asignamos la variable para usar despues
            $permissions = $data['permissions'];
            // Removemos del array
            unset($data['permissions']);
            // Creamos el registro
            $record = static::getModel()::create($data);
            // Sincronizamos los permisos
            $record->syncPermissions($permissions);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            Notification::make()
                ->title('Ocurrio un error')
                ->danger()
                ->send();

            $this->halt();
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

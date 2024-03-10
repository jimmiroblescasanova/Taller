<x-filament-panels::page>

    <x-filament::section aside>
        <x-slot name="heading">
            Clientes
        </x-slot>

        <x-slot name="description">
            Obtener todos los clientes de la empresa CONTPAQi asociada.
        </x-slot>

        <x-filament-panels::form wire:submit="submit" wire:loading.remove>

            {{ $this->form }}

            <x-filament::button type="submit">
                Sincronizar
            </x-filament::button>
                
        </x-filament-panels::form>

        <div wire:loading> 
            Sincronización iniciada... Por favor no recargues la página.
            <x-filament::loading-indicator class="h-5 w-5" /> 
        </div>

    </x-filament::section>

</x-filament-panels::page>
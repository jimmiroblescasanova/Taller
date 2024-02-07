<x-filament-panels::page>

    <x-filament::section aside>
        <x-slot name="heading">
            Sincronizacion de productos
        </x-slot>

        <x-slot name="description">
            Esta sección es para sincronizar los productos y servicios del sistema CONTPAQi al sistema Admin Taller
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

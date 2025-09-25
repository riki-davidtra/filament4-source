<x-filament-panels::page>
    <div class="max-w-xl w-full">
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary" wire:target="save" wire:loading.attr="disabled" spinner class="mt-6">
                Save Changes
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>

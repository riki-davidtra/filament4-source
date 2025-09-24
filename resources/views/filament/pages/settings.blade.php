<x-filament-panels::page>
    <div class="max-w-xl w-full">
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            {{ $this->form }}

            <x-filament::button type="submit" color="primary" wire:loading.attr="disabled" wire:loading.class="cursor-not-allowed" class="mt-6">
                <span class="flex items-center">
                    <span wire:loading wire:target="save">
                        <x-filament::loading-indicator class="w-5 h-5 mr-2" />
                    </span>
                    <span>Save Changes</span>
                </span>
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page>

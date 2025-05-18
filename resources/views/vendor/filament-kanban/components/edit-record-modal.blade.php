<x-filament-panels::form wire:submit.prevent="editModalFormSubmitted">
    <x-filament::modal id="kanban--edit-record-modal" :slideOver="$this->getEditModalSlideOver()" :width="$this->getEditModalWidth()">
        <x-slot name="header">
            <x-filament::modal.heading>
                {{ $this->getEditModalTitle() }}
            </x-filament::modal.heading>
        </x-slot>

       
            {{ $this->infolist }}
       

        <x-slot name="footer">
            <x-filament::button color="gray" x-on:click="isOpen = false">
                Close
            </x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::form>

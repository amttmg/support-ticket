<x-dynamic-component :component="static::isSimple() ? 'filament-panels::page.simple' : 'filament-panels::page'">
    <x-filament-panels::form id="form" wire:submit="save">
        {{ $this->form }}
        @livewire(\App\Filament\Widgets\SupportUnitsWidget::class)

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
</x-dynamic-component>

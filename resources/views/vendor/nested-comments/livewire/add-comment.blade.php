<div>
    @if ($this->addingComment)
        <form wire:submit.prevent="create" wire:loading.attr="disabled" class="space-y-4">
            {{ $this->form }}
            <x-filament::button type="submit">
                Submit
            </x-filament::button>
            <x-filament::button type="button" color="gray" wire:click="showForm(false)">
                Cancel
            </x-filament::button>
        </form>
    @else
        @if (
            $this->commentable->status_id !== App\Constants\TicketStatusConstant::CLOSED &&
                $this->commentable->status_id !== App\Constants\TicketStatusConstant::REJECTED)
            {{-- <x-filament::input.wrapper :inline-prefix="true" prefix-icon="heroicon-o-chat-bubble-bottom-center-text"> --}}
            <x-filament::button color="primary" wire:click.prevent.stop="showForm(true)">
                {{ $this->replyTo?->getKey() ? 'Add a reply' : 'Add a new comment' }}
            </x-filament::button>
            {{-- </x-filament::input.wrapper> --}}
        @endif
    @endif
    <x-filament-actions::modals />
</div>

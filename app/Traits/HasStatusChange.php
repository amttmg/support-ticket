<?php

namespace App\Traits;

trait HasStatusChange
{
    public function updateStatus(int $id, int $statusId): void
    {
        $record = \App\Models\Ticket::findOrFail($id);
        $record->update(['status_id' => $statusId]);

        $this->dispatch('notify', type: 'success', message: 'Status updated!');
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class SupportUnitsWidget extends Widget
{
    protected static string $view = 'filament.widgets.support-units-widget';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'half';


    public function getSupportUnits()
    {
        // Replace with your actual relationship logic
        return User::find(auth()->id()) // Or whatever identifies your record
            ->supportUnits()
            ->latest()
            ->limit(5)
            ->get();
    }
}

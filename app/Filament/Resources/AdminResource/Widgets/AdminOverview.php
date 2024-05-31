<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Guest', Guest::count()),
            Stat::make('Total Booking', Booking::count()),
            Stat::make('Total Room', Room::count())
        ];
    }
}

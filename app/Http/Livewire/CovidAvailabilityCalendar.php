<?php

namespace App\Http\Livewire;

use Asantibanez\LivewireCalendar\LivewireCalendar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CovidAvailabilityCalendar extends LivewireCalendar
{
    public function events() : Collection {
        return collect([
            [
                'id' => 1,
                'title' => 'Breakfast',
                'date' => Carbon::today(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'date' => Carbon::tomorrow(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'date' => Carbon::tomorrow(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'date' => Carbon::tomorrow(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'date' => Carbon::tomorrow(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'date' => Carbon::tomorrow(),
            ],
        ]);
    }
}

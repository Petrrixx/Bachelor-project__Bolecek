<?php

namespace App\Rules;

use App\Models\Reservation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FitsIntoCapacity implements ValidationRule
{
    public function __construct(
        private string $dateField,
        private string $timeField,
        private string $guestsField
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $value je počet hostí v práve spracovávanej rezervácii
        $data   = request()->all();
        $date   = $data[$this->dateField];
        $time   = $data[$this->timeField];

        $window = (int) config('reservation.time_window');              // 90
        $cap    = (int) config('reservation.capacity');                 // 40

        $start  = now()->createFromFormat('Y-m-d H:i', "$date $time")
                       ->subMinutes($window)->format('H:i:s');
        $end    = now()->createFromFormat('Y-m-d H:i', "$date $time")
                       ->addMinutes($window)->format('H:i:s');

        $already = Reservation::whereDate('date', $date)
            ->whereBetween('time', [$start, $end])
            ->sum('guests');

        if ($already + $value > $cap) {
            $fail('Kapacita reštaurácie je v danom čase už naplnená.');
        }
    }
}

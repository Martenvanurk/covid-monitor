<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\storeAvailability;
use App\Models\Availability as AvailabilityModel;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Availability extends Controller
{

    public function index(Request $request)
    {
        $week = $request->week ?? Carbon::now()->weekOfYear;
        $year = $request->year ?? Carbon::now()->year;

        try {
            Assert::range($week, 1, 53);
            Assert::length($year, 4);
        } catch ( InvalidArgumentException $e) {
            $week = Carbon::now()->weekOfYear;
            $year = Carbon::now()->year;
        }

        $date = Carbon::now()->setISODate($year, $week)->locale('nl_NL');
        $datesOfWeek = collect(CarbonPeriod::between($date->startOfWeek(), $date->copy()->endOfWeek()));

        $dates = $datesOfWeek->map(function($dateOfWeek) {
            return [
                'identifier' => \Carbon\Carbon::parse($dateOfWeek)->translatedFormat('Y-m-d'),
                'translated' => ucfirst(\Carbon\Carbon::parse($dateOfWeek)->translatedFormat('l j F Y')),
                'availableUsers' => AvailabilityModel::select('users.*')
                    ->join('users', 'users.id', '=', 'availability.user_id')
                    ->where('availability.date_availability', \Carbon\Carbon::parse($dateOfWeek)->translatedFormat('Y-m-d'))
                    ->get()
            ];
        });

        $availableDatesForUser = AvailabilityModel::where('user_id', auth()->user()->id)
            ->whereBetween('date_availability', [$dates[0]['identifier'], $dates[6]['identifier']])->get();

        $datesUser = [];
        foreach($availableDatesForUser as $rowUserDate) {
            $datesUser[$rowUserDate->date_availability] = $rowUserDate;
        }

        return view( 'availability.index',
            compact( ['dates', 'week', 'year', 'datesUser'] )
        );
    }

    public function store(storeAvailability $request)
    {
        $validated = $request->validated();

        return AvailabilityModel::create($validated);
    }

    public function destroy(storeAvailability $request)
    {
        $validated = $request->validated();

        return AvailabilityModel::where('date_availability', $validated['date_availability'])
            ->where('user_id', $validated['user_id'])
            ->delete();
    }
}

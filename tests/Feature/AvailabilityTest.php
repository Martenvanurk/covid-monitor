<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function testThatAQuestCannotChooseTheirAvailability(): void
    {
        $this->get('availability')
            ->assertRedirect('/login');
    }

    public function testThatLoggedInUsersCanChooseTheirAvailabilityByCurrentWeeknumber(): void
    {
        $this->signIn();

        $today = Carbon::now()->locale('nl_NL');
        $currentWeekNumber = $today->weekOfYear;

        $startOfWeek = $today->startOfWeek();
        $endOfWeek = $today->endOfWeek();

        $this->get('availability')
            ->assertSee('Aanwezig')
            ->assertSee('Afwezig')
            ->assertSee($currentWeekNumber)
            ->assertSee($startOfWeek->isoFormat('LL'))
            ->assertSee($endOfWeek->isoFormat('LL'));
    }

    public function testThatLoggedInUsersCanChooseTheirAvailabilityByDefinedWeeknumber(): void
    {
        $this->signIn();

        $this->get('availability?year=2020&week=15')
            ->assertSee('Aanwezig')
            ->assertSee('Afwezig')
            ->assertSee('Week: 15 - 2020')
            ->assertSee('Maandag 6 april')
            ->assertSee('Zaterdag 11 april');
    }

    public function testThatTheAdministratorCanSeeAListOfPeopleAtTheOffice(): void
    {
        $this->signIn();

        $this->get('dashboard')
            ->assertSee('Week');
    }

    public function testThatQuestsCannotProvideTheirAvailability(): void
    {
        $this->post('availability/store')
            ->assertRedirect('/login');
    }

    public function testThatSignedInUserCanProvideTheirAvailability(): void
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $attributes = [
            'date_availability' => Carbon::now()->format('Y-m-d'),
            'user_id' => auth()->user()->id,
            'at_office' => 1
        ];

        $availability = $this->post('/availability/store', $attributes);

        $this->assertDatabaseHas('availability', $attributes);
    }


    public function testThatSignedInUserCanDeleteTheirAvailability(): void
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $attributes = [
            'date_availability' => Carbon::now()->format('Y-m-d'),
            'user_id' => auth()->user()->id,
            'at_office' => 1
        ];

        $availability = $this->post('/availability/store', $attributes);

        $this->post('/availability/destroy', $attributes);

        $this->assertDatabaseMissing('availability', $attributes);
    }

    public function testThatTheAvailableUsersCanBeFetchedByDate(): void
    {
        $this->withoutExceptionHandling();

        $currentDate = Carbon::now()->format('Y-m-d');

        Availability::factory()->count(10)->create(['date_availability' => $currentDate]);

        $eventsForDate = (new Availability)->getAllAvailableUsersByDate($currentDate);

        $firstEvent = $eventsForDate->first();

        $this->assertSame(10, $eventsForDate->count());
        $this->assertTrue($eventsForDate->contains('id', $firstEvent->id));
        $this->assertTrue($eventsForDate->contains('user_id', $firstEvent->id));
    }

    public function testThatTheUserCanBeFetchedFromTheEvent(): void
    {
        $this->withoutExceptionHandling();

        $currentDate = Carbon::now()->format('Y-m-d');

        Availability::factory()->create(['date_availability' => $currentDate]);

        $eventsForDate = (new Availability)->getAllAvailableUsersByDate($currentDate);

        $firstEvent = $eventsForDate->first();

        $user = User::where('id', $firstEvent->user_id)->first();

        $this->assertSame($user->name, $firstEvent->user->name);
    }
}

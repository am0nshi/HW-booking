<?php

namespace Tests\Feature\Booking;

use App\Http\Models\Entity;
use App\Http\Models\EntityBooking;
use App\Http\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Base booking class / seeder
 *
 * Class GetBookingListTest
 * @package Tests\Feature\Booking
 */
abstract class BaseBookingTest extends TestCase
{
    use DatabaseTransactions;

    protected $url = null;

    /**
     * Generate fake test data
     *
     * @return mixed
     */
    protected function generateFakeBookings()
    {
        $user = User::create([
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'company' => 'company',
        ]);
        $entity = Entity::create([
            'name' => 'test',
        ]);

        EntityBooking::create([
            'from' => Carbon::create(2019, 01, 01, 01, 01, 01),
            'to' => Carbon::create(2019, 01, 01, 01, 01, 02),
            'user_id' => $user->id,
            'entity_id' => $entity->id,
        ]);
        EntityBooking::create([
            'from' => Carbon::create(2019, 01, 01, 01, 01, 03),
            'to' => Carbon::create(2019, 01, 01, 01, 01, 04),
            'user_id' => $user->id,
            'entity_id' => $entity->id,
        ]);
        EntityBooking::create([
            'from' => Carbon::create(2019, 01, 01, 01, 01, 05),
            'to' => Carbon::create(2019, 01, 01, 01, 01, 06),
            'user_id' => $user->id,
            'entity_id' => $entity->id,
        ]);
        EntityBooking::create([
            'from' => Carbon::create(2019, 01, 01, 01, 01, 07),
            'to' => Carbon::create(2019, 01, 01, 01, 01, 8),
            'user_id' => $user->id,
            'entity_id' => $entity->id,
        ]);

        $this->url = route('booking.create', ['entity' => $entity->id]);

        return $entity->id;
    }
}

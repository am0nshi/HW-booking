<?php

namespace Tests\Unit\Services\Booking;

use App\Exceptions\Booking\BookingNotReadyException;
use App\Http\Models\Entity;
use App\Http\Models\EntityBooking;
use App\Services\Booking\BookingManagementService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Basic test for booking management service
 *
 * Class BookingManagementServiceTest
 * @package Tests\Unit\Services\Booking
 */
class BookingManagementServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beginDatabaseTransaction();
    }

    public function testCreateBooking_givenEmptyGetBookings_expectsBookingToBeCreated()
    {
        $this->assertDatabaseMissing('entity_bookings', [
            'from' => '2020-01-01 01:01:01',
            'to' => '2020-01-01 01:01:02',
        ]);

        $builder = \Mockery::mock(Builder::class);
        $builder->shouldReceive('exists')
            ->andReturn(false);

        $service = \Mockery::mock(BookingManagementService::class)->makePartial();
        $service->shouldReceive('getBookings')
            ->andReturn($builder);

        $this->assertInstanceOf(EntityBooking::class, $service->createBooking(
            Entity::create([
                'name' => 'any'
            ]),
            [
                'first_name' => 1,
                'last_name' => 2,
                'booked_from' => '01-01-2020 01:01:01',
                'booked_to' => '01-01-2020 01:01:02',
            ]
        ));

        $this->assertDatabaseHas('entity_bookings', [
            'from' => '2020-01-01 01:01:01',
            'to' => '2020-01-01 01:01:02',
        ]);
    }

    public function testCreateBooking_givenNonEmptyGetBookings_expectsExceptionToBeThrown()
    {
        $this->expectException(BookingNotReadyException::class);
        $this->expectExceptionMessage("Booking not possible, room is not available");

        $builder = \Mockery::mock(Builder::class);
        $builder->shouldReceive('exists')
            ->andReturn(true);

        $service = \Mockery::mock(BookingManagementService::class)->makePartial();
        $service->shouldReceive('getBookings')
            ->andReturn($builder);

        $service->createBooking(
            Entity::create([
                'name' => 'any'
            ]),
            [
                'first_name' => 1,
                'last_name' => 2,
                'booked_from' => '01-01-2020 01:01:01',
                'booked_to' => '01-01-2020 01:01:02',
            ]
        );

        $this->assertDatabaseMissing('entity_bookings', [
            'from' => '2020-01-01 01:01:01',
            'to' => '2020-01-01 01:01:02',
        ]);
    }
}

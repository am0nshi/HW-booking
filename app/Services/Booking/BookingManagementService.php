<?php

namespace App\Services\Booking;

use App\Exceptions\Booking\BookingNotReadyException;
use App\Http\Models\Entity;
use App\Http\Models\EntityBooking;
use App\Http\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Get matched bookings service
 *
 * Class GetBookingsService
 * @package App\Services\Booking
 */
class BookingManagementService
{
    /**
     * @param Entity $entity
     * @param Carbon $from
     * @param Carbon $to
     *
     * @return Builder
     */
    public function getBookings(Entity $entity, Carbon $from, Carbon $to)
    {
        return EntityBooking::where('entity_id', $entity->id)
            ->where(function ($query) use ($from, $to) {
                $query->where('from', '<=', $to);
                $query->where('to', '>=', $from);
            });
    }

    /**
     * Create entity booking record
     *
     * @param Entity $entity
     * @param        $data
     *
     * @return mixed
     * @throws BookingNotReadyException
     */
    public function createBooking(Entity $entity, $data)
    {
        $from = new Carbon($data['booked_from']);
        $to = new Carbon($data['booked_to']);

        if ($this->getBookings($entity, $from, $to)->exists()) {
            throw new BookingNotReadyException(202, "Booking not possible, room is not available");
        }

        $user = User::firstOrCreate([
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'company' => $data['company'] ?? '',
        ]);

        $entityBooking = EntityBooking::create([
            'from' => $from,
            'to' => $to,
            'entity_id' => $entity->id,
            'user_id' => $user->id,
        ]);

        return $entityBooking;
    }
}

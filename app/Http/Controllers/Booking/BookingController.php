<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Models\Entity;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\GetBookingListRequest;
use App\Http\Resources\EntityBookingResource;
use App\Services\Booking\BookingManagementService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Booking controller
 *`
 * Class BookingController
 * @package App\Http\Controllers\Booking
 */
class BookingController extends Controller
{
    /**
     * Get booking list
     *
     * @param GetBookingListRequest $request
     * @param Entity                $entity
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getBookingList(GetBookingListRequest $request, Entity $entity)
    {
        $entityBookings = app(BookingManagementService::class)->getBookings(
            $entity,
            new Carbon($request->booked_from),
            new Carbon($request->booked_to)
        )->orderBy('from')->with('User')->get();

        return EntityBookingResource::collection($entityBookings);
    }

    /**
     * Create booking series
     *
     * @param CreateBookingRequest $request
     * @param Entity               $entity
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Throwable
     */
    public function createBooking(CreateBookingRequest $request, Entity $entity)
    {
        try {
            DB::beginTransaction();

            $entityBookings = collect([]);

            foreach ($request->all() as $data) {
                $entityBookings[] = app(BookingManagementService::class)->createBooking($entity, $data);
            }

            DB::commit();

            return EntityBookingResource::collection($entityBookings);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}

<?php

namespace Tests\Feature\Booking;

use App\Http\Models\Entity;
use App\Http\Models\EntityBooking;
use App\Http\Models\User;
use Carbon\Carbon;

/**
 * Get booking list
 *
 * Class GetBookingListTest
 * @package Tests\Feature\Booking
 */
class GetBookingListTest extends BaseBookingTest
{
    /**
     * Date range check
     */
    public function test_givenPredefinedBookings_expects200Response()
    {
        $this->beginDatabaseTransaction();

        $entityId = $this->generateFakeBookings();

        $scenarios = [
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z', 'booked_to' => '01-01-2019T01:01:02Z']) => [
                'data' => [
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:01Z',
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z', 'booked_to' => '01-01-2019T01:01:03Z']) => [
                'data' => [
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:01Z',
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ],
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:03Z',
                        'booked_to' => '2019-01-01T01:01:04Z',
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:00Z', 'booked_to' => '01-01-2019T01:01:01Z']) => [
                'data' => [
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:01Z',
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:00Z', 'booked_to' => '01-01-2019T01:01:15Z']) => [
                'data' => [
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:01Z',
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ],
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:03Z',
                        'booked_to' => '2019-01-01T01:01:04Z',
                    ],
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:05Z',
                        'booked_to' => '2019-01-01T01:01:06Z',
                    ],
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:07Z',
                        'booked_to' => '2019-01-01T01:01:08Z',
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:06Z', 'booked_to' => '01-01-2019T01:01:15Z']) => [
                'data' => [
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:05Z',
                        'booked_to' => '2019-01-01T01:01:06Z',
                    ],
                    [
                        'user' => [
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'company' => 'company',
                        ],
                        'booked_from' => '2019-01-01T01:01:07Z',
                        'booked_to' => '2019-01-01T01:01:08Z',
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2020T01:01:01Z', 'booked_to' => '01-01-2020T01:01:15Z']) => [
                'data' => []
            ],
        ];

        foreach ($scenarios as $url => $json) {
            $this->runTestScenario($url, $json, 200);
        }
    }

    /**
     * Empty db check
     */
    public function test_givenEmptyBookings_expects200EmptyResponse()
    {
        $this->beginDatabaseTransaction();

        $entityId = $this->generateFakeBookings();

        $response = $this->get(route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z', 'booked_to' => '01-01-2019T01:01:02Z']));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);
    }

    public function test_runFailedTestScenarios_expects405ErrorAndProperJSON()
    {
        $this->beginDatabaseTransaction();

        $entityId = $this->generateFakeBookings();

        $scenarios = [
            route('booking.list', ['entity' => $entityId]) => [
                'errors' => [
                    'booked_from' => [
                        "The booked from field is required."
                    ],
                    'booked_to' => [
                        "The booked to field is required."
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '123']) => [
                'errors' => [
                    'booked_from' => [
                        "The booked from is not a valid date."
                    ],
                    'booked_to' => [
                        "The booked to field is required."
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z']) => [
                'errors' => [
                    'booked_to' => [
                        "The booked to field is required."
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z', 'booked_to' => '123']) => [
                'errors' => [
                    'booked_to' => [
                        "The booked to is not a valid date."
                    ]
                ]
            ],
            route('booking.list', ['entity' => $entityId, 'booked_from' => '01-01-2019T01:01:01Z', 'booked_to' => '01-01-2019T01:01:01Z']) => [
                'errors' => [
                    'booked_from' => [
                        "The booked from must be a date before booked to."
                    ]
                ]
            ],
        ];

        foreach ($scenarios as $url => $json) {
            $this->runTestScenario($url, $json, 405);
        }
    }

    /**
     * Given both empty filter values
     *
     * @return void
     */
    protected function runTestScenario($url, $json, $code)
    {
        $response = $this->get($url);

        $response->assertStatus($code);
        $response->assertJson($json);
        if (200 == $code) {
            $response->assertJsonCount(count($json['data']), 'data');
        }
    }
}

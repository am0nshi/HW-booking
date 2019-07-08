<?php

namespace Tests\Feature\Booking;

use App\Http\Models\Entity;
use App\Http\Models\EntityBooking;
use App\Http\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Get booking list
 *
 * Class GetBookingListTest
 * @package Tests\Feature\Booking
 */
class CreateBookingTest extends BaseBookingTest
{
    /**
     * Date range check
     */
    public function test_givenNormalRange_expects200Ok()
    {
        $this->beginDatabaseTransaction();

        $this->generateFakeBookings();

        $scenarios = [
            [
                'code' => 200,
                'request' => [
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '2020-01-01T01:01:01Z',
                        'booked_to' => '2020-01-01T01:01:02Z',
                    ]
                ],
                'response' => [
                    'data' => [
                        [
                            'booked_from' => '2020-01-01T01:01:01Z',
                            'booked_to' => '2020-01-01T01:01:02Z',
                        ]
                    ]
                ]
            ]
        ];

        foreach ($scenarios as $scenario) {
            $this->runTestScenario($scenario);
        }
    }
    /**
     * Date range check
     */
    public function test_givenPredefinedBookings_expects202Response()
    {
        $this->beginDatabaseTransaction();

        $this->generateFakeBookings();

        $scenarios = [
            [
                'code' => 202,
                'request' => [
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '2019-01-01T01:01:01Z',
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ]
                ],
                'response' => [
                    'message' => "Booking not possible, room is not available"
                ]
            ],
            [
                'code' => 202,
                'request' => [
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '2020-01-01T01:01:01Z', //passed
                        'booked_to' => '2020-01-01T01:01:02Z',
                    ],
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '2019-01-01T01:01:01Z', //not passed
                        'booked_to' => '2019-01-01T01:01:02Z',
                    ]
                ],
                'response' => [
                    'message' => "Booking not possible, room is not available"
                ]
            ]
        ];

        foreach ($scenarios as $scenario) {
            $this->runTestScenario($scenario);
        }
    }

    /**
     * Validation check
     */
    public function test_givenBrokenValidationRules_expects405Errors()
    {
        $this->beginDatabaseTransaction();

        $this->generateFakeBookings();

        $scenarios = [
            [
                'code' => 405,
                'request' => [
                    [
                        'first_name' => '',
                        'last_name' => '',
                        'company' => '',
                        'booked_from' => '',
                        'booked_to' => '',
                    ]
                ],
                'response' => [
                    'errors' => [
                        '0.first_name' => [
                            "The 0.first_name field is required."
                        ],
                        '0.last_name' => [
                            "The 0.last_name field is required."
                        ],
                        '0.booked_from' => [
                            "The 0.booked_from field is required."
                        ],
                        '0.booked_to' => [
                            "The 0.booked_to field is required."
                        ]
                    ]
                ]
            ],
            [
                'code' => 405,
                'request' => [
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '',
                        'booked_to' => '',
                    ]
                ],
                'response' => [
                    'errors' => [
                        '0.booked_from' => [
                            "The 0.booked_from field is required."
                        ],
                        '0.booked_to' => [
                            "The 0.booked_to field is required."
                        ]
                    ]
                ]
            ],
            [
                'code' => 405,
                'request' => [
                    [
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '01-01-2019T01:01:01Z',
                        'booked_to' => '01-01-2019T01:01:01Z',
                    ]
                ],
                'response' => [
                    'errors' => [
                        '0.booked_from' => [
                            "The 0.booked_from must be a date before 0.booked_to."
                        ],
                    ]
                ]
            ],
            [
                'code' => 405,
                'request' => [
                    [ //passing
                        'first_name' => 'first',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '01-01-2019T01:01:01Z',
                        'booked_to' => '01-01-2019T01:01:02Z',
                    ],
                    [ //firstname is missing
                        'first_name' => '',
                        'last_name' => 'last',
                        'company' => '',
                        'booked_from' => '01-01-2019T01:01:01Z',
                        'booked_to' => '01-01-2019T01:01:02Z',
                    ]
                ],
                'response' => [
                    'errors' => [
                        '1.first_name' => [
                            "The 1.first_name field is required."
                        ],
                    ]
                ]
            ]
        ];

        foreach ($scenarios as $scenario) {
            $this->runTestScenario($scenario);
        }
    }

    /**
     * Run scenario and assert response values
     *
     * @return void
     */
    protected function runTestScenario($scenario)
    {
        $response = $this->postJson($this->url, $scenario['request']);

        $response->assertStatus($scenario['code']);
        $response->assertJson($scenario['response']);
    }
}

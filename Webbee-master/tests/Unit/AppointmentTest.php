<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testCreateAppointmentForMultipleUserSuccess()
    {
        $appointmentData = [
            'service_id' => 1,
            'date' => '2023-06-05',
            'time' => '10:30',
            'users' => [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                ],
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                ],
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                ],
            ]
        ];

        $response = $this->postJson('/api/appointment', $appointmentData);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Appointment booked successfully.',
        ]);
    }

    public function testCreateAppointmentForSingleUserSuccess()
    {
        $appointmentData = [
            'service_id' => 1,
            'date' => '2023-06-05',
            'time' => '10:45',
            'users' => [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                ],
            ]
        ];

        $response = $this->postJson('/api/appointment', $appointmentData);

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Appointment booked successfully.',
        ]);
    }


    public function testCreateAppointmentMissingData()
    {
        $response = $this->postJson('/api/appointment', []);

        $response->assertStatus(422);

        // $response->assertJsonValidationErrors([
        //     'service_id',
        //     'date',
        //     'time',
        //     'users',
        // ]);
    }

    public function testCreateAppointmentInvalidServiceId()
    {

        $appointmentData = [
            'service_id' => 999,
            'date' => '2023-06-05',
            'time' => '10:00',
            'users' => [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                ],
            ]
        ];

        $response = $this->postJson('/api/appointment', $appointmentData);

        $response->assertStatus(422);

        // $response->assertJson([
        //     'service_id' => 'The selected service id is invalid.',
        // ]);
    }
}

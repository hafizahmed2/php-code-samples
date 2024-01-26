<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Service;

class MenHaircutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = Service::create([
            'name' => 'Men Haircut',
            'time_requried' => '10',
            'cleaning_break' => '5',
        ]);

        // Configure opening hours for Men Haircut
        $service->openingHours()->createMany([
            [
                'day_of_week' => 1,
                'start_time' => '08:00',
                'end_time' => '20:00',
            ],
            [
                'day_of_week' => 2,
                'start_time' => '08:00',
                'end_time' => '20:00',
            ],
            [
                'day_of_week' => 3, 
                'start_time' => '08:00',
                'end_time' => '20:00',
            ],
            [
                'day_of_week' => 4, 
                'start_time' => '08:00',
                'end_time' => '20:00',
            ],
            [
                'day_of_week' => 5, 
                'start_time' => '08:00',
                'end_time' => '20:00',
            ],
            [
                'day_of_week' => 6, 
                'start_time' => '10:00',
                'end_time' => '22:00',
            ],
        ]);

        // Configure breaks for Men Haircut
        $service->breaktimes()->createMany([
            [
                'start_time' => '12:00',
                'end_time' => '13:00',
            ],
            [
                'start_time' => '15:00',
                'end_time' => '16:00',
            ],
        ]);

        // Configure other settings for Men Haircut (max clients, slot duration, etc.)

        // Generate slots for the next 7 days
        $startDate = Carbon::now()->startOfDay();
        $endDate = $startDate->copy()->addDays(6)->endOfDay();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $this->generateSlots($currentDate, $service);
            $currentDate->addDay();
        }
    }

    private function generateSlots(Carbon $date, Service $service)
    {
        $startTime = Carbon::createFromTime(8, 0);
        $endTime = Carbon::createFromTime(22, 0);

        // Add slots every 10 minutes
        while ($startTime->lte($endTime)) {

            // Check if the current time falls within the opening hours and breaks
            if ($this->isWithinOpeningHours($service, $date, $startTime) && !$this->isWithinBreaks($service, $startTime)) {
                $service->slots()->create([
                    'date' => $date,
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $startTime->addMinutes($service->time_requried + $service->cleaning_break)->format('H:i'),
                    'max_clients' => 3,
                ]);
            } else {
                $startTime->addMinutes(10);
            }
        }
    }

    private function isWithinOpeningHours(Service $service, Carbon $date, Carbon $time)
    {
        $openingHours = $service->openingHours->where('day_of_week', $date->dayOfWeek)->first();

        if (!$openingHours) {
            return false;
        }

        $openingTime = Carbon::parse($openingHours['start_time']);
        $closingTime = Carbon::parse($openingHours['end_time']);

        return $time->between($openingTime, $closingTime);
    }

    private function isWithinBreaks(Service $service, Carbon $time)
    {
        $breaks = $service->breaktimes;
        $serviceEndTime = $time->copy()->addMinutes($service->time_requried + $service->cleaning_break);

        foreach ($breaks as $break) {
            $startTime = Carbon::parse($break['start_time'])->addMinutes(1);
            $endTime = Carbon::parse($break['end_time'])->subMinutes(1);

            if (($serviceEndTime->between($startTime, $endTime) || $time->between($startTime, $endTime)) && $endTime != $time) {
                return true;
            }
        }

        return false;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'users' => 'required|array|min:1',
                'users.*.first_name' => 'required',
                'users.*.last_name' => 'required',
                'users.*.email' => 'required|email',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $serviceId = $request->service_id;
        $date = $request->date;
        $time = $request->time;
        $users = $request->users;

        // Get the selected service
        $service = Service::findOrFail($serviceId);

        // Check if the requested slot is valid and available
        $isSlotAvailable = $this->isSlotAvailable($service, $date, $time);

        if (!$isSlotAvailable) {
            return response()->json(['message' => 'The requested slot is not available.'], 400);
        }

        if(count($users) > $isSlotAvailable[0]->max_clients){
            return response()->json(['message' => 'Number of clients should not be greater than '.$isSlotAvailable[0]->max_clients], 400);
        }

        // Create the appointment and associate it with the service
        $appointment = new Appointment();
        $appointment->service()->associate($service);

        // Set the appointment date and time
        $dateTime = Carbon::parse($date . ' ' . $time);
        $appointment->appointment_date = $dateTime;
        $appointment->users = json_encode($users);
        $appointment->slot_id = $isSlotAvailable[0]->id;

        // Save the appointment
        $appointment->save();

        return response()->json(['message' => 'Appointment booked successfully.'], 201);
    }

    private function isSlotAvailable(Service $service, $date, $time)
    {
        $slots = $service->slots()->whereDate('date', $date)->where("start_time",$time)->get();

        if ($slots->count() == 0) {
            return false;
        }
        $appointments = $slots[0]->appointments()->count();
        // Check if the slot is already booked
        if ($appointments != 0) {
            return false;
        }

        return $slots;
    }


}

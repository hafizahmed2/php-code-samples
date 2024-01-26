<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        $service = Service::findOrFail(2);
        $daysOfWeek = range(0, 6);

        $slotsByDay = [];

        foreach ($daysOfWeek as $key => $dayOfWeek) {
            $date = Carbon::now()->addDays($key)->format('Y-m-d');

            $slots = $service->slots()
                ->whereDate('date', $date)
                ->orderBy('start_time')
                ->get();

            $slotsByDay["$service->name"]["$date"] = $slots;
        }

        return response()->json($slotsByDay);
    }
}

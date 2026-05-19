<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Barber;
use App\Http\Controllers\TimeSlotController;

#[Signature('app:generate-daily-slots')]
#[Description('Generate daily time slots for barbers')]
class GenerateDailySlots extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date('w');
        $barbers = Barber::where('day_of_week', $today)->where('is_active', true)->get();

        foreach($barbers as $barber){
            $controller = new TimeSlotController();
            $controller->generate($barber->id, $today);
        }
    }
}

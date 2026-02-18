<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update 15 vehicles to have budget-friendly pricing of 50,000 UGX per day
        // Targeting economy and mid-range vehicles
        DB::table('cars')
            ->whereIn('model', [
                'Noah',
                'Spacio',
                'Fielder',
                'Allex',
                'Runx',
                'Auris',
                'Rumion',
                'Isis',
                'Passo',
                'Premio',
                'Avensis',
                'Harrier',
                'Kluger',
                'Vanguard',
                'RAV4'
            ])
            ->update(['dailyRate' => 50000]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally restore original prices if needed
        // This is a placeholder - you'd need to store original prices to truly reverse
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeCarPictureNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check which column exists and make it nullable
        $columns = Schema::getColumnListing('cars');
        
        if (in_array('carPicture', $columns)) {
            DB::statement('ALTER TABLE cars MODIFY carPicture VARCHAR(100) NULL');
        } elseif (in_array('car_picture', $columns)) {
            DB::statement('ALTER TABLE cars MODIFY car_picture VARCHAR(100) NULL');
        } elseif (in_array('image', $columns)) {
            DB::statement('ALTER TABLE cars MODIFY image VARCHAR(100) NULL');
        } else {
            // Add carPicture column if it doesn't exist
            DB::statement('ALTER TABLE cars ADD COLUMN carPicture VARCHAR(100) NULL AFTER id');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = Schema::getColumnListing('cars');
        
        if (in_array('carPicture', $columns)) {
            DB::statement('ALTER TABLE cars MODIFY carPicture VARCHAR(100) NOT NULL');
        }
    }
}

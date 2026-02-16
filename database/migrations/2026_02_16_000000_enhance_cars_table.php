<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('transmission', 20)->default('Automatic')->after('seats');
            $table->string('fuel_type', 20)->default('Petrol')->after('transmission');
            $table->integer('year')->nullable()->after('fuel_type');
            $table->text('description')->nullable()->after('year');
            $table->json('features')->nullable()->after('description');
            $table->boolean('is_featured')->default(false)->after('features');
            $table->integer('view_count')->default(0)->after('is_featured');
            $table->integer('booking_count')->default(0)->after('view_count');
            $table->decimal('rating', 3, 2)->default(0)->after('booking_count');
            $table->string('luggage_capacity', 20)->nullable()->after('rating');
            $table->string('doors', 10)->default('4')->after('luggage_capacity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'transmission',
                'fuel_type',
                'year',
                'description',
                'features',
                'is_featured',
                'view_count',
                'booking_count',
                'rating',
                'luggage_capacity',
                'doors'
            ]);
        });
    }
}

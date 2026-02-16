<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingFields extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('bookings', 'customerEmail')) {
                $table->string('customerEmail')->nullable()->after('customerName');
            }
            if (!Schema::hasColumn('bookings', 'customerPhone')) {
                $table->string('customerPhone')->nullable()->after('customerEmail');
            }
            if (!Schema::hasColumn('bookings', 'idType')) {
                $table->string('idType')->nullable()->after('mobile_money_number');
            }
            if (!Schema::hasColumn('bookings', 'idNumber')) {
                $table->string('idNumber')->nullable()->after('idType');
            }
            if (!Schema::hasColumn('bookings', 'idDocument')) {
                $table->string('idDocument')->nullable()->after('idNumber');
            }
            if (!Schema::hasColumn('bookings', 'totalPrice')) {
                $table->decimal('totalPrice', 12, 2)->nullable()->after('pricing');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'customerEmail')) {
                $table->dropColumn('customerEmail');
            }
            if (Schema::hasColumn('bookings', 'customerPhone')) {
                $table->dropColumn('customerPhone');
            }
            if (Schema::hasColumn('bookings', 'idType')) {
                $table->dropColumn('idType');
            }
            if (Schema::hasColumn('bookings', 'idNumber')) {
                $table->dropColumn('idNumber');
            }
            if (Schema::hasColumn('bookings', 'idDocument')) {
                $table->dropColumn('idDocument');
            }
            if (Schema::hasColumn('bookings', 'totalPrice')) {
                $table->dropColumn('totalPrice');
            }
        });
    }
}

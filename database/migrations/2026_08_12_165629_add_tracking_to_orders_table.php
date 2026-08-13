<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('status');
            $table->string('courier_name')->nullable()->after('tracking_number');
            $table->text('tracking_url')->nullable()->after('courier_name');
            $table->json('tracking_history')->nullable()->after('tracking_url');
            $table->timestamp('shipped_at')->nullable()->after('tracking_history');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'courier_name',
                'tracking_url',
                'tracking_history',
                'shipped_at',
                'delivered_at'
            ]);
        });
    }
};

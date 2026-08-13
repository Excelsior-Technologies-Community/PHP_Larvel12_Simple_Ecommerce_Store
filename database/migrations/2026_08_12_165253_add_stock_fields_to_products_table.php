<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('status');
            $table->integer('stock_quantity')->default(0)->after('sku');
            $table->integer('low_stock_threshold')->default(5)->after('stock_quantity');
            $table->boolean('track_stock')->default(true)->after('low_stock_threshold');
            $table->boolean('allow_backorder')->default(false)->after('track_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'stock_quantity', 'low_stock_threshold', 'track_stock', 'allow_backorder']);
        });
    }
};

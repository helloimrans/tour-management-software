<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->nullable();
            $table->string('app_slogan')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('app_background_image')->nullable();
            $table->bigInteger('point_per_coupon')->nullable();
            $table->boolean('is_point_by_registration')->default(false);
            $table->bigInteger('point_per_registration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

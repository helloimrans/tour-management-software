<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('per_member_cost', 12, 2)->default(0);
            $table->integer('max_members')->default(0);
            $table->string('image')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'closed'])->default('upcoming');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};

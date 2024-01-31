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
        Schema::create('online_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable()->default(null);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('link')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_events');
    }
};

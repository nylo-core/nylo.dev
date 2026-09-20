<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Online events moved to config/project/events.php — there are about four a
 * year and each ships with a cover image, so they are a deploy either way.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('online_events');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};

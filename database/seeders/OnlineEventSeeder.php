<?php

namespace Database\Seeders;

use App\Models\OnlineEvent;
use Illuminate\Database\Seeder;

class OnlineEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OnlineEvent::create([
            'title' => 'Nylo v7 Live Q&A',
            'description' => 'A live walkthrough of Nylo v7 with community questions and answers.',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(7)->addHours(2),
            'link' => 'https://www.youtube.com/@nylo_dev',
        ]);
    }
}

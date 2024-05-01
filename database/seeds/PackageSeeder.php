<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $repositories = ['nylo', 'framework', 'support', 'media-pro', 'permission-policy', 'device-meta', 'error-stack'];
        $organization = 'nylo-core';

        foreach ($repositories as $repository) {
            Package::factory([
                'repository' => $repository,
                'organization' => $organization,
                'version' => '1.0.0',
                'site' => 'github'
            ])->create();
        }
    }
}

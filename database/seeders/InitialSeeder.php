<?php

namespace Database\Seeders;

use App\Models\Repository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $repositories = Storage::json('init.json');

        foreach ($repositories as $repository) {
            Repository::updateOrCreate(
                ['package_name' => $repository['package_name']],
                Arr::except($repository, ['package_name'])
            );
        }
    }
}

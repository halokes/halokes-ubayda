<?php

namespace Database\Seeders;

use App\Models\Ubayda\Business;
use App\Models\Ubayda\BusinessUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Initialize Faker
        $faker = Faker::create('id_ID');

        // Step 1: Select 5 random users for each role
        $owners = User::inRandomOrder()->take(30)->get();
        $editors = User::inRandomOrder()->take(30)->get();
        $viewers = User::inRandomOrder()->take(30)->get();

        // Step 2: For each owner, create a business and assign roles
        foreach ($owners as $index => $owner) {
            // Create a new business with random name and address from Faker
            $business = Business::create([
                'id' => Str::uuid(),
                'name' => $faker->company,
                'address' => $faker->address,
                'type' => $faker->randomElement(['UKM', 'PT/CV', 'Pendidikan']),
            ]);

            // Attach the owner with 'owner' role
            BusinessUser::create([
                'id' => Str::uuid(),
                'user_id' => $owner->id,
                'business_id' => $business->id,
                'role' => 'OWNER',
                'created_by'    => 'seeder',
                'updated_by'    => 'seeder',
            ]);

            // Attach an editor to the business (corresponding editor for this business)
            $editor = $editors[$index];
            BusinessUser::create([
                'id' => Str::uuid(),
                'user_id' => $editor->id,
                'business_id' => $business->id,
                'role' => 'EDITOR',
                'created_by'    => 'seeder',
                'updated_by'    => 'seeder',
            ]);

            // Attach a viewer to the business (corresponding viewer for this business)
            $viewer = $viewers[$index];
            BusinessUser::create([
                'id' => Str::uuid(),
                'user_id' => $viewer->id,
                'business_id' => $business->id,
                'role' => 'VIEWER',
                'created_by'    => 'seeder',
                'updated_by'    => 'seeder',
            ]);
        }
    }
}

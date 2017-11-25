<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 100;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('events')->insert([ //,
                'title' => $faker->text($maxNbChars = 100)  ,
                'date_time' => $faker->iso8601($max = '+2 years'),
                'image_url' => $faker->imageUrl($width = 640, $height = 480),
                'available_seats' => $faker->numberBetween($min = 1000, $max = 9000),
                'location' => $faker->city,
                'created_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'updated_at' => $faker->date($format = 'Y-m-d', $max = 'now'),
            ]);
        }
    }
}

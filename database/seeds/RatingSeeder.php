<?php

use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $count = 100;

        for ($i = 0; $i <= $count; $i++) {
            $complain = new \App\Rating();
            $complain->order_id = $faker->randomNumber(6);
            $complain->outlet_id = \Illuminate\Support\Arr::random(\App\Outlet::pluck('id')->toArray());
            $complain->ticket_status_id = \Illuminate\Support\Arr::random(\App\TicketStatus::pluck('id')->toArray());
            $complain->user_id = \Illuminate\Support\Arr::random(\App\User::pluck('id')->toArray());
            $complain->customer_id = \Illuminate\Support\Arr::random(\App\Customer::pluck('id')->toArray());
            $complain->desc = $faker->text;
            $complain->remarks = $faker->slug;
            $complain->created_at = $faker->dateTimeThisMonth;
            $complain->informed_to = \Illuminate\Support\Arr::random(['Aslam', 'Fawad', 'Rahmat', 'Sabir', 'Ahmad', 'Aftab', 'Bilal', 'Sameer', 'Zulqarnain', 'Hamid', 'Raza', 'Saqib']);
            $complain->save();

            $complain->issues()->sync(\Illuminate\Support\Arr::random(\App\Issue::pluck('id')->toArray(), 2));
        }
    }
}

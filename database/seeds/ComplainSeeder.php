<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ComplainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $count = 500;

        for ($i = 0; $i <= $count; $i++) {
            $complain = new \App\Complain();
            $complain->title = $faker->text(10);
            $complain->order_id = $faker->randomNumber(6);
            $complain->outlet_id = \Illuminate\Support\Arr::random(\App\Outlet::pluck('id')->toArray());
            $complain->ticket_status_id = \Illuminate\Support\Arr::random(\App\TicketStatus::pluck('id')->toArray());
            $complain->user_id = \Illuminate\Support\Arr::random(\App\User::pluck('id')->toArray());
            $complain->customer_id = \Illuminate\Support\Arr::random(\App\Customer::pluck('id')->toArray());
            $complain->desc = $faker->text;
            $complain->remarks = $faker->slug;
            $complain->created_at = $faker->dateTimeThisMonth;
            $complain->save();

            $complain->issues()->sync(\Illuminate\Support\Arr::random(\App\Issue::pluck('id')->toArray(), 2));
        }
    }
}

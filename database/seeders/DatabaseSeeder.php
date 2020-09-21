<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @var User $toby
         */
        $toby = User::factory(
            [
                'email'                      => 'toby@powellblyth.com',
                'password'                   => Hash::make('moo'),
                'name'                       => 'Toby',
                'family_name'                => 'Powell-Blyth',
                'notify_about_failed_orders' => true,
            ]
        )->create();

        $mainTeam = Team::factory(
            [
                'name'          => 'admins',
                'personal_team' => false,
                'user_id'       => $toby,
            ]
        )->create();

//        $toby->personalTeam()->associate($mainTeam);
        $toby->current_team_id = $mainTeam->id;
        $toby->save();

        $sean = User::factory(
            [
                'password'                   => Hash::make('moo'),
                'name'                       => 'Sean',
                'family_name'                => 'Farrell',
                'email'                      => 'sean@chateaurouge.co.uk',
                'notify_about_failed_orders' => true,
            ]
        )->create();
        $sean->current_team_id = $mainTeam->id;
        $sean->save();


    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SeedUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('users')) {
            $user = DB::table('users')->where("email", "=", "test@app.com")->first();
            DB::transaction(function () use ($user) {
                $user = DB::table('users')->where("email", "=", "test@app.com")->first();
                if (!$user) {
                    $userId = DB::table('users')->insertGetId([
                        "name" => "test",
                        "email" => "test@app.com",
                        "password" => Hash::make("password"),
                        "created_at" => now(),
                        "updated_at" => now(),
                        "email_verified_at" => now(),
                    ]);
                }
            });
        } else {
            abort(500, "The users table does not exist. Ensure you run the migration before running this seeder.");
        }
    }
}

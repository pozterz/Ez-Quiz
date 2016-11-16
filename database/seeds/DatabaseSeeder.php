<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call('UserTableSeeder');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
class UserTableSeeder extends Seeder {
    public function run() {    
        factory(User::class,19)->create();
    }    

}

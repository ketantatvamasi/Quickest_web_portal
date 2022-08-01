<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class BussinesCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_categories')->delete();
	    $company_categories = array(
            array('id' => 1,'name' => 'Solar'),
            array('id' => 2,'name' => 'Electric Bike'),
            array('id' => 3,'name' => 'It'),
        );
        DB::table('company_categories')->insert($company_categories);
    }
}

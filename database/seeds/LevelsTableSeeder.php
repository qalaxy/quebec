<?php

use Illuminate\Database\Seeder;

use App\Level;
class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = array(
					array('name'=>'super_admin', 'order'=>1),
					array('name'=>'system_admin', 'order'=>2),
					array('name'=>'admin', 'order'=>3),
					array('name'=>'super_user', 'order'=>4),
					array('name'=>'user', 'order'=>5),
		);
		
		for($i = 0; $i < count($levels); $i++){
			Level::firstOrCreate(array('name'=>$levels[$i]['name']),
					array('name'=>$levels[$i]['name'], 'order'=>$levels[$i]['order'])
			);
		}
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Station;
use App\Func;

class StationFunctionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $station_func = array(array('station'=>'Headquarters', 'function'=>'AIS Products Management'),
						array('station'=>'Jomo Kenyatta International Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Jomo Kenyatta International Airport NOF', 'function'=>'AIS Products Management'),
						array('station'=>'Nairobi Wilson Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Moi International Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Eldoret International Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Kisumu Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Malindi Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Wajir Airport', 'function'=>'AIS Products Management'),
						array('station'=>'Lokochoggio Airport', 'function'=>'AIS Products Management'),
						
						array('station'=>'Jomo Kenyatta International Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Nairobi Wilson Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Moi International Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Eldoret International Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Kisumu Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Malindi Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Wajir Airport', 'function'=>'Flight Planning Management'),
						array('station'=>'Lokochoggio Airport', 'function'=>'Flight Planning Management'),
						
						array('station'=>'Headquarters', 'function'=>'Maps and Charts Management'),
						array('station'=>'Headquarters', 'function'=>'Terrain and Obstacle Data Management'),
						array('station'=>'Headquarters', 'function'=>'Instrument/Visual flight procedure design'),
						
						array('station'=>'Headquarters', 'function'=>'Technical library'),
						array('station'=>'Jomo Kenyatta International Airport', 'function'=>'Technical library'),
						array('station'=>'Jomo Kenyatta International Airport NOF', 'function'=>'Technical library'),
						array('station'=>'Nairobi Wilson Airport', 'function'=>'Technical library'),
						array('station'=>'Moi International Airport', 'function'=>'Technical library'),
						array('station'=>'Eldoret International Airport', 'function'=>'Technical library'),
						array('station'=>'Kisumu Airport', 'function'=>'Technical library'),
						array('station'=>'Malindi Airport', 'function'=>'Technical library'),
						array('station'=>'Wajir Airport', 'function'=>'Technical library'),
						array('station'=>'Lokochoggio Airport', 'function'=>'Technical library'),
		);
		
		$stations = Station::all();
		$functions = Func::all();
		
		for($i = 0; $i < count($station_func); $i++){
			foreach($stations as $station){
				if($station_func[$i]['station'] == $station->name){
					foreach($functions as $function){
						if($station_func[$i]['function'] == $function->name){
							$station->func()->attach($function);
						}
					}
				}
			}
		}
    }
}

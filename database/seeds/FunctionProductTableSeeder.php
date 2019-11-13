<?php

use Illuminate\Database\Seeder;
use App\Func;
use App\Product;

class FunctionProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $func_prod = array(array('function'=>'AIS Products Management', 'product'=>'AIP AMDT'),
						array('function'=>'AIS Products Management', 'product'=>'AIC'),
						array('function'=>'AIS Products Management', 'product'=>'AIRAC AIP SUP'),
						array('function'=>'AIS Products Management', 'product'=>'AIP SUP'),
						array('function'=>'AIS Products Management', 'product'=>'Request NOTAM'),
						array('function'=>'AIS Products Management', 'product'=>'Promulgated NOTAM'),
						array('function'=>'AIS Products Management', 'product'=>'AIRAC AIP AMDT'),
						
						array('function'=>'Flight Planning Management', 'product'=>'Station ACFT movement statistics reports'),
						array('function'=>'Flight Planning Management', 'product'=>'Customer Service Requesition Form - CSRF'),
						array('function'=>'Flight Planning Management', 'product'=>'Transmitted FPL associated ATS messages'),
						array('function'=>'Flight Planning Management', 'product'=>'PIB issued'),
						array('function'=>'Flight Planning Management', 'product'=>'Approved flight plans transmitted to designated recipients'),
						
						
						array('function'=>'Maps and Charts Management', 'product'=>'Digital datasets'),
						array('function'=>'Maps and Charts Management', 'product'=>'Special charts by stakeholders'),
						array('function'=>'Maps and Charts Management', 'product'=>'World Aeronautical Chart'),
						array('function'=>'Maps and Charts Management', 'product'=>'Topographic Charts'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aerodrome Movement Chart – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aerodrome Parking Chart- ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aerodrome Chart - ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Visual Approach Chart – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Standard Arrival Chart – Instrument(STAR) – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Standard Departure Chart – Instrument(SID) – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Area Chart – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'En-route chart – ICAO'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aeronautical Obstacle chart – ICAO type C'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aeronautical Obstacle chart – ICAO type B'),
						array('function'=>'Maps and Charts Management', 'product'=>'Aeronautical Obstacle chart – ICAO type A'),
						array('function'=>'Maps and Charts Management', 'product'=>'Instrument approach chart - ICAO'),
						
						array('function'=>'Terrain and Obstacle Data Management', 'product'=>'Digital datasets'),
						array('function'=>'Terrain and Obstacle Data Management', 'product'=>'Validated Obstacle data'),
						array('function'=>'Terrain and Obstacle Data Management', 'product'=>'Validated Terrain data'),
						
						array('function'=>'Instrument/Visual flight procedure design', 'product'=>'IAPs'),
						array('function'=>'Instrument/Visual flight procedure design', 'product'=>'STARs'),
						array('function'=>'Instrument/Visual flight procedure design', 'product'=>'SIDs'),
						array('function'=>'Instrument/Visual flight procedure design', 'product'=>'Validated visual flight procedures'),
						
						array('function'=>'Technical library', 'product'=>'ICAO Documents'),
						array('function'=>'Technical library', 'product'=>'Foreign AIM products'),
		
		);
		
		$functions = Func::all();
		$products = Product::all();
		
		for($i = 0; $i < count($func_prod); $i++){
			foreach($functions as $function){
				if($func_prod[$i]['function'] == $function->name){
					foreach($products as $product){
						if($func_prod[$i]['product'] == $product->name){
							$function->product()->attach($product);
						}
					}
				}
			}
		}
    }
}

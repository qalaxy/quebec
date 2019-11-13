<?php

use Illuminate\Database\Seeder;

use App\Product;
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = array(array('name'=>'AIRAC AIP AMDT', 'description'=>''),
						array('name'=>'Promulgated NOTAM', 'description'=>''),
						array('name'=>'Request NOTAM', 'description'=>''),
						array('name'=>'AIP SUP', 'description'=>''),
						array('name'=>'AIRAC AIP SUP', 'description'=>''),
						array('name'=>'AIC', 'description'=>''),
						array('name'=>'AIP AMDT', 'description'=>''),
						
						array('name'=>'Approved flight plans transmitted to designated recipients', 'description'=>''),
						array('name'=>'PIB issued', 'description'=>''),
						array('name'=>'Transmitted FPL associated ATS messages', 'description'=>''),
						array('name'=>'Customer Service Requesition Form - CSRF', 'description'=>''),
						array('name'=>'Station ACFT movement statistics reports', 'description'=>''),
						
						array('name'=>'Instrument approach chart - ICAO', 'description'=>''),
						array('name'=>'Aeronautical Obstacle chart – ICAO type A', 'description'=>''),
						array('name'=>'Aeronautical Obstacle chart – ICAO type B', 'description'=>''),
						array('name'=>'Aeronautical Obstacle chart – ICAO type C', 'description'=>''),
						array('name'=>'En-route chart – ICAO', 'description'=>''),
						array('name'=>'Area Chart – ICAO', 'description'=>''),
						array('name'=>'Standard Departure Chart – Instrument(SID) – ICAO', 'description'=>''),
						array('name'=>'Standard Arrival Chart – Instrument(STAR) – ICAO', 'description'=>''),
						array('name'=>'Visual Approach Chart – ICAO', 'description'=>''),
						array('name'=>'Aerodrome Chart - ICAO', 'description'=>''),
						array('name'=>'Aerodrome Parking Chart- ICAO', 'description'=>''),
						array('name'=>'Aerodrome Movement Chart – ICAO', 'description'=>''),
						array('name'=>'Topographic Charts', 'description'=>''),
						array('name'=>'World Aeronautical Chart', 'description'=>''),
						array('name'=>'Special charts by stakeholders', 'description'=>''),
						array('name'=>'Digital datasets', 'description'=>''),
						
						array('name'=>'Validated Terrain data', 'description'=>''),
						array('name'=>'Validated Obstacle data', 'description'=>''),
						
						array('name'=>'Validated visual flight procedures', 'description'=>''),
						array('name'=>'SIDs', 'description'=>''),
						array('name'=>'STARs', 'description'=>''),
						array('name'=>'IAPs', 'description'=>''),
						
						array('name'=>'ICAO Documents', 'description'=>''),
						array('name'=>'Foreign AIM products', 'description'=>''),
			);
			
		for($i = 0; $i < count($products); $i++){
			Product::firstOrCreate(array('name'=>$products[$i]['name']), 
								array('uuid'=>Uuid::generate(),
									'name'=>$products[$i]['name'],
									'description'=>$products[$i]['description'],
								));
		}
    }
}

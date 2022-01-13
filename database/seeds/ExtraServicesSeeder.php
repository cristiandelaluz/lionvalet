<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtraServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('extra_services')->insert([
            [
                'name' => 'Lavage intégrale', 
                'price' => 65,
                'img_name' => 'wash.svg'
            ],
            [
                'name' => 'Lavage extérieur', 
                'price' => 35,
                'img_name' => 'wash-out.svg'
            ],
            [
                'name' => 'Lavage intérieur', 
                'price' => 35,
                'img_name' => 'wash-in.svg'
            ],
            [
                'name' => 'Ajustement de pneu', 
                'price' => 5,
                'img_name' => 'tire.svg'
            ],
            [
                'name' => "Plein d'essence", 
                'price' => 15,
                'img_name' => 'room.svg'
            ],
            [
                'name' => 'Entretien climatisation', 
                'price' => 90,
                'img_name' => 'document.svg'
            ],
            [
                'name' => 'Révision de la voiture', 
                'price' => 310,
                'img_name' => 'motor.svg'
            ],
            [
                'name' => 'Annulation', 
                'price' => 7,
                'img_name' => 'refund.png'
            ],
        ]);
    }
}

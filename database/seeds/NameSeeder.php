<?php

use Illuminate\Database\Seeder;
use App\Name;

class NameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = config('data.names', []); // in data config ho salvato i dati da inserire nella tabella

        for ($i=0; $i < count($data) ; $i++) { 
            
            // in data ci sono coppie di nomi italiano/inglese. per ogni data quindi creo due record uno con language italian e il name in italiano e l'altro per l'inglese.
            Name::create([
                'idNode'   => $i + 1,
                'language' => 'english',
                'name'     => $data[$i][0]
            ]);

            Name::create([
                'idNode'   => $i + 1,
                'language' => 'italian',
                'name'     => $data[$i][1]
            ]);

        }
    }
}

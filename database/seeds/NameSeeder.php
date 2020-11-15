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
        $data = config('data.names', []);

        for ($i=0; $i < count($data) ; $i++) { 
            
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

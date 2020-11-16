<?php

use Illuminate\Database\Seeder;
use App\Node;

class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = config('data.nodes', []); // in data config ho salvato i dati da inserire nella tabella

        foreach ($data as $node) {
            Node::create($node);
        }
    }
}

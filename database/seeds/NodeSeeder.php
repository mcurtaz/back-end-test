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
        $data = config('data.nodes', []);

        foreach ($data as $node) {
            Node::create($node);
        }
    }
}

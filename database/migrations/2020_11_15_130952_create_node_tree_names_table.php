<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodeTreeNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_tree_names', function (Blueprint $table) {

            $table -> id();
            $table -> foreignId('idNode');
            $table -> enum('language', ['english ', 'italian']);
            $table -> string('name');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_tree_names');
    }
}

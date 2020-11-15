<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNodeTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_tree', function (Blueprint $table) {

            $table -> bigIncrements('idNode');
            $table -> unsignedInteger('level');
            $table -> unsignedInteger('iLeft');
            $table -> unsignedInteger('iRight');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_tree');
    }
}

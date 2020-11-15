<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('node_tree_names', function (Blueprint $table) {
            
            $table -> foreign('idNode', 'idNode-foreign')
                   -> references('idNode')
                   -> on('node_tree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('node_tree_names', function (Blueprint $table) {

            $table -> dropForeign('idNode-foreign');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProfessionIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::table('users', function (Blueprint $table) {
            
              $table->BigInteger('profession_id')->unsigned()->after('password')->nullable();       
            $table->foreign('profession_id')->references('id')->on('professions');
        });
        
       //Schema::enableForeignKeyConstraints();
        //Schema::disableForeignKeyConstraints();
    }

    public function down()
    {
        
        Schema::table('users', function (Blueprint $table) {
             $table->dropForeign(['profession_id']);
            $table->dropColumn('profession_id');
        });
         
    }
}

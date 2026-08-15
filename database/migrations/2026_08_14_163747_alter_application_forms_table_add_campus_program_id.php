<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationFormsTableAddCampusProgramId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(Schema::hasColumn('application_forms', 'campus_bank_id')){
            return;
        }
        Schema::table('application_forms', function(Blueprint $table){
            $table->integer('campus_bank_id')->nullable();
            $table->string('bank_receipt_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

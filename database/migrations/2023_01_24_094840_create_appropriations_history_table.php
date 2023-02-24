<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appropriations_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');            
            $table->enum('owner_type',['scheme','department']);
            $table->float('amount',20,2);
            $table->json("appropriation");//[{name:'',percentage_dividend:'',amount:''}]
            $table->unsignedBigInteger("source_id")->nullable();
            $table->string("description")->nullable();   
            $table->string('fund_category');    
            $table->string("fund_month_year")->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appropriations');
    }
};

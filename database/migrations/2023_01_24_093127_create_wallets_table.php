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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("wallet_number")->unique()->index()->nullable();
            $table->enum("owner_type",['App\\\Models\\\Scheme','App\\\Models\\\Appropriation']);
            $table->unsignedBigInteger("owner_id");
            $table->float("balance",15,2)->default(0.00);
            $table->string("fund_month_year")->nullable();
            $table->string('fund_category')->default(''); 
            $table->float("safe_balance",15,2)->default(0.00);        
            $table->float("transits",15,2)->default(0.00);    //fake                
            $table->float("total_collection",15,2)->default(0.00);    
            $table->unsignedBigInteger("source_id")->nullable();
            $table->string("description")->nullable();        
            $table->string("token")->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};

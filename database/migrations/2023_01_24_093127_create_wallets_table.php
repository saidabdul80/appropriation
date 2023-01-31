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
            $table->unsignedBigInteger("wallet_number")->unique()->index();
            $table->enum("owner_type",['App\\\Models\\\Scheme','App\\\Models\\\Appropriation']);
            $table->unsignedBigInteger("owner_id");
            $table->float("balance",15,2)->default(0.00);
            $table->date('fund_month_year')->nullable();
            $table->float("safe_balance",15,2)->default(0.00);        
            $table->float("transits",15,2)->default(0.00);    //fake                
            $table->float("total_collection",15,2)->default(0.00);    
            $table->enum("source",['BHCPF Funding (FG)','State Counterpart Funding','Premium Sales','Formal Sector Premium','TiSHIP Premium','Other Source']);
            $table->string("description")->nullable();        
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

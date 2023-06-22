<?php

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("transaction_id")->references('id')->on('transactions');
            $table->foreignUuid("product_id")->references('id')->on('products');
            $table->integer("quantity");
            $table->integer("subtotal");
            $table->timestamps();
        });
        
        DB::statement('ALTER TABLE transaction_details ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}
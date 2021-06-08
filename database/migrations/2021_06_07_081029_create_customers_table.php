<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string("name")->nullable(); ///:"Prof. Simeon Green",
            $table->string("address")->nullable(); ///:"328 Bergstrom Heights Suite 709 49592 Lake Allenville",
            $table->boolean("checked"); ///:false,
            $table->text("description")->nullable(); ///:
            $table->string("interest")->nullable(); ///
            $table->dateTime("date_of_birth")->nullable(); ///:"1989-03-21T01:11:13+00:00",
            $table->string("email", 181)->nullable();///:"nerdman@cormier.net",
            $table->string("account")->nullable(); ///:"556436171909",
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
        Schema::dropIfExists('customers');
    }
}

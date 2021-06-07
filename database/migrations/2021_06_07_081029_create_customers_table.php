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

            $table->string("name"); ///:"Prof. Simeon Green",
            $table->string("address"); ///:"328 Bergstrom Heights Suite 709 49592 Lake Allenville",
            $table->boolean("checked"); ///:false,
            $table->text("description"); ///:
            $table->string("interest"); ///
            $table->dateTime("date_of_birth")->nullable(); ///:"1989-03-21T01:11:13+00:00",
            $table->string("email", 181)->unique(); ///:"nerdman@cormier.net",
            $table->string("account"); ///:"556436171909",
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

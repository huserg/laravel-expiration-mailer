<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expirations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('expiration_date');
            $table->json('emails');
            $table->text('message')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expirations');
    }
};

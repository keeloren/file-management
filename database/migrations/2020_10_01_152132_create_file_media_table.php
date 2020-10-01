<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_media', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
            $table->string('name')->nullable();
            $table->string('file_name_original');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_media');
    }
}

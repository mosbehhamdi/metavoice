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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name')->nullable();
            $table->longText('description');
            $table->integer('admin_id');
            $table->integer('worker_id');
            $table->boolean('hasFile');
            $table->string('status')->default('En attente');
            $table->string('adminName');
            $table->string('adminPhoto');
            $table->string('workerName');
            $table->string('workerPhoto');
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
        Schema::dropIfExists('tasks');
    }
};

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
        Schema::create('task_responces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('admin_id');
            $table->integer('worker_id');
            $table->string('workerName')->nullable();
            $table->integer('task_id');
            $table->string('andminName')->nullable();
            $table->string('andminPhoto')->nullable();
            $table->string('workerPhoto')->nullable();

            $table->string('taskTitle')->nullable();
        



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
        Schema::dropIfExists('task_responces');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('b_t_l_s', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no');
            $table->string('name');
            $table->string('cnic');
            $table->string('mobile');
            $table->string('tell_no');
            $table->string('project_type');
            $table->string('phase');
            $table->string('plot_size');
            $table->string('sector');
            $table->string('plot_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b_t_l_s');
    }
};

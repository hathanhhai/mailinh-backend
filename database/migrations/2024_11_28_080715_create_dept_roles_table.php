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
        Schema::create('dept_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('api_url');
            $table->string('page_url');
            $table->boolean('active')->default(1);
            $table->string('menu');
            $table->unsignedInteger('menu_parent_id')->nullable();
            $table->integer('menu_index');
            $table->date('deleted_at')->nullable();
            $table->integer('created_id')->nullable();
            $table->unsignedInteger('updated_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dept_roles');
    }
};

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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('guid', 36)->unique();
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->text('social_link_1')->nullable();
            $table->text('social_link_2')->nullable();
            $table->text('social_link_3')->nullable();
            $table->text('social_link_4')->nullable();           
            $table->string("media_id")->nullable();
            $table->integer("created_by")->nullable();
            $table->integer("status")->default(1);
            $table->integer("is_active")->default(1);
            $table->integer("is_deleted")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

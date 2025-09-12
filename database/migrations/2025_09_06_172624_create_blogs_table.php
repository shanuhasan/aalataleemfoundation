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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('meta_tag')->nullable();
            $table->string('meta_desc')->nullable();
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
        Schema::dropIfExists('blogs');
    }
};

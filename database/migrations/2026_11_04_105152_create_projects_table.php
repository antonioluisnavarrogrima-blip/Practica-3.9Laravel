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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();                                            // id INT AUTO_INCREMENT
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 1:N "Crea" (relación con users)
            $table->string('title');                                 // title VARCHAR
            $table->text('description');                             // description TEXT
            $table->date('due_date');                                // due_date DATE
            $table->string('status');                                // status VARCHAR
            $table->timestamps();                                    // created_at y updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

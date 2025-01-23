<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Project_has_Platform', function (Blueprint $table) {
            $table->unsignedBigInteger('Project_Id');
            $table->unsignedBigInteger('Platform_Id');
            
            // กำหนด Composite Primary Key
            $table->primary(['Project_Id', 'Platform_Id']);
            
            // สร้าง Foreign Key
            $table->foreign('Project_Id')->references('Id_Project')->on('ListProjectModel')->onDelete('cascade');
            $table->foreign('Platform_Id')->references('Id_Platform')->on('Platform')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Project_has_Platform');
    }
};

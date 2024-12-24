<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Project_has_Sustainable_Development_Goals', function (Blueprint $table) {
             $table->unsignedBigInteger('Project_Id');
             $table->unsignedBigInteger('SDGs_Id');
 
             // กำหนด Primary Key แบบ Composite Key
             $table->primary(['Project_Id', 'SDGs_Id']);
 
             // สร้าง Foreign Key
             $table->foreign('Project_Id')->references('Id_Project')->on('Project')->onDelete('cascade');
             $table->foreign('SDGs_Id')->references('id_SDGs')->on('Sustainable_Development_Goals')->onDelete('cascade');
 
             $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Project_has_Sustainable_Development_Goals');
    }
};

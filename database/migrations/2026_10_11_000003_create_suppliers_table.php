<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name', 150);
            $table->string('contact_person', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 255)->nullable();
            // true = aktif, false = diarsipkan (soft delete sederhana, bisa dipulihkan)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('supplier_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

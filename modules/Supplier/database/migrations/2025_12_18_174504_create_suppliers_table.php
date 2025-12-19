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
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('credit_limit')->nullable();
            $table->decimal('default_reduction')->default(0);
            $table->string('address')->nullable();
            $table->string('type')->nullable();

            // Business identifiers (local and international)
            $table->string('ice', 64)->nullable()->unique(); // Identifiant Commun de l'Entreprise (Morocco)
            $table->string('rc', 64)->nullable()->unique();  // Registre de Commerce
            $table->string('tax_id', 64)->nullable()->unique(); // Generic tax identifier / IF
            $table->string('vat_number', 64)->nullable()->unique(); // VAT/TVA number

            // Banking identifiers
            $table->string('rib', 64)->nullable()->unique(); // Bank account RIB
            $table->string('iban', 64)->nullable()->unique(); // International Bank Account Number
            // Additional banking identifiers
            $table->string('swift_bic', 11)->nullable()->unique();
            $table->string('account_number', 64)->nullable()->unique();
            $table->string('routing_number', 64)->nullable()->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

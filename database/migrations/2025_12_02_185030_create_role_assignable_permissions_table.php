<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_assignable_permissions', function (Blueprint $table) {
            // Role របស់អ្នកដែលចែកសិទ្ធិ (ឧ. Admin)
            $table->unsignedBigInteger('role_id'); 
            
            // Permission ដែលត្រូវបានអនុញ្ញាតឱ្យចែក (ឧ. user-create)
            $table->unsignedBigInteger('permission_id'); 

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            
            $table->primary(['role_id', 'permission_id'], 'role_perm_assign_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_assignable_permissions');
    }
};

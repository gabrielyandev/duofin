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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('installment_group_id')->nullable()->constrained('installment_groups')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->index();
            $table->date('paid_at')->nullable();
            $table->enum('type', ['expense', 'income'])->default('expense');
            $table->enum('payment_method', ['cash', 'credit_card', 'pix', 'debit'])->default('pix');
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->unsignedInteger('installment_number')->nullable();
            $table->unsignedInteger('total_installments')->nullable();
            $table->timestamps();

            $table->index(['workspace_id', 'due_date']);
            $table->index(['workspace_id', 'status']);
            $table->index(['workspace_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

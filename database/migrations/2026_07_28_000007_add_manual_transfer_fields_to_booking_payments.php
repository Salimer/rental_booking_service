<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('booking_payments')) {
            return;
        }

        Schema::table('booking_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_payments', 'sender_name')) {
                $table->string('sender_name', 255)->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('booking_payments', 'transfer_proof_name')) {
                $table->string('transfer_proof_name', 255)->nullable()->after('sender_name');
            }

            if (! Schema::hasColumn('booking_payments', 'transfer_proof_path')) {
                $table->string('transfer_proof_path', 500)->nullable()->after('transfer_proof_name');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('booking_payments')) {
            return;
        }

        Schema::table('booking_payments', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('booking_payments', 'transfer_proof_path')) {
                $columns[] = 'transfer_proof_path';
            }

            if (Schema::hasColumn('booking_payments', 'transfer_proof_name')) {
                $columns[] = 'transfer_proof_name';
            }

            if (Schema::hasColumn('booking_payments', 'sender_name')) {
                $columns[] = 'sender_name';
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

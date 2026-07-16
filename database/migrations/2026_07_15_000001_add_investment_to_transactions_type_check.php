<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Allow the new INVESTMENT transaction type in the transactions.type check constraint.
     * The column is a varchar with a CHECK ((type)::text = ANY (ARRAY[...])) constraint
     * (Laravel's enum() on PostgreSQL), so new types must be added to that constraint.
     */
    public function up()
    {
        DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_type_check');
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_type_check CHECK ((type)::text = ANY ((ARRAY[
            'ADD-MONEY'::character varying,
            'MONEY-OUT'::character varying,
            'WITHDRAW'::character varying,
            'COMMISSION'::character varying,
            'BONUS'::character varying,
            'TRANSFER-MONEY'::character varying,
            'MONEY-EXCHANGE'::character varying,
            'ADD-SUBTRACT-BALANCE'::character varying,
            'MAKE-PAYMENT'::character varying,
            'CAPITAL-RETURN'::character varying,
            'OTHER-BANK-TRANSFER'::character varying,
            'OWN-BANK-TRANSFER'::character varying,
            'MOBILE-WALLET-TRANSFER'::character varying,
            'VIRTUAL-CARD'::character varying,
            'INVESTMENT'::character varying,
            'Salary Disbursement'::character varying
        ]::text[])))");
    }

    public function down()
    {
        DB::statement('ALTER TABLE transactions DROP CONSTRAINT IF EXISTS transactions_type_check');
        DB::statement("ALTER TABLE transactions ADD CONSTRAINT transactions_type_check CHECK ((type)::text = ANY ((ARRAY[
            'ADD-MONEY'::character varying,
            'MONEY-OUT'::character varying,
            'WITHDRAW'::character varying,
            'COMMISSION'::character varying,
            'BONUS'::character varying,
            'TRANSFER-MONEY'::character varying,
            'MONEY-EXCHANGE'::character varying,
            'ADD-SUBTRACT-BALANCE'::character varying,
            'MAKE-PAYMENT'::character varying,
            'CAPITAL-RETURN'::character varying,
            'OTHER-BANK-TRANSFER'::character varying,
            'OWN-BANK-TRANSFER'::character varying,
            'MOBILE-WALLET-TRANSFER'::character varying,
            'VIRTUAL-CARD'::character varying,
            'Salary Disbursement'::character varying
        ]::text[])))");
    }
};

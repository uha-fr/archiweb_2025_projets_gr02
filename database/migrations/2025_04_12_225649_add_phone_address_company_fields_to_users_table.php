<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneAddressCompanyFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone_number')->nullable()->after('bio');
        $table->string('address')->nullable()->after('phone_number');
        $table->string('company_name')->nullable()->after('address');
        $table->string('tax_id')->nullable()->after('company_name');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['phone_number', 'address', 'company_name', 'tax_id']);
    });
}

}

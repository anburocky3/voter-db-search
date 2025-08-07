<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voter_data', function (Blueprint $table) {
            $table->id();
            $table->string('ST_CODE', 10)->nullable();
            $table->integer('AC_NO')->nullable();
            $table->integer('PART_NO')->nullable();
            $table->integer('SLNOINPART')->nullable();
            $table->string('HOUSE_NO_EN', 50)->nullable();
            $table->integer('SECTION_NO')->nullable();
            $table->string('FM_NAME_EN')->nullable();
            $table->string('FM_NAME_V1')->nullable();
            $table->string('LASTNAME_EN')->nullable();
            $table->string('LASTNAME_V1')->nullable();
            $table->string('RLN_TYPE', 10)->nullable();
            $table->string('RLN_FM_NM_EN')->nullable();
            $table->string('RLN_FM_NM_V1')->nullable();
            $table->string('RLN_L_NM_EN')->nullable();
            $table->string('RLN_L_NM_V1')->nullable();
            $table->string('IDCARD_NO', 50)->nullable();
            $table->string('STATUSTYPE', 10)->nullable();
            $table->integer('PARTLINKNO')->nullable();
            $table->string('SEX', 10)->nullable();
            $table->integer('AGE')->nullable();
            $table->integer('ORGNLISTNO')->nullable();
            $table->string('ORGN_TYPE', 10)->nullable();
            $table->integer('CHNGLISTNO')->nullable();
            $table->string('CHNG_TYPE', 10)->nullable();
            $table->integer('WARD_NO')->nullable();
            $table->string('FIELD_3', 10)->nullable();
            $table->string('DLT_REASON')->nullable();
            $table->string('OLD_HOUSENO', 50)->nullable();
            $table->text('ADDRESS1_EN')->nullable();
            $table->text('ADDRESS1_V1')->nullable();
            $table->text('ADDRESS2_EN')->nullable();
            $table->text('ADDRESS2_V1')->nullable();
            $table->text('ADDRESS3_EN')->nullable();
            $table->text('ADDRESS3_V1')->nullable();
            $table->string('UIDNO', 20)->nullable();
            $table->string('MOBILENO', 15)->nullable();
            $table->string('ac_Name_en')->nullable();
            $table->string('ac_Name_ta')->nullable();
            $table->text('address')->nullable();
            $table->text('addressta')->nullable();
            $table->timestamps();

            // Performance indexes for search functionality

            // Single column indexes for common searches
            $table->index('FM_NAME_EN');
            $table->index('LASTNAME_EN');
            $table->index('IDCARD_NO');
            $table->index('UIDNO');
            $table->index('MOBILENO');
            $table->index('HOUSE_NO_EN');

            // Filter field indexes
            $table->index('AC_NO');
            $table->index('PART_NO');
            $table->index('WARD_NO');
            $table->index('SEX');
            $table->index('AGE');
            $table->index('STATUSTYPE');
            $table->index('RLN_TYPE');
            $table->index('SLNOINPART');

            // Composite indexes for common filter combinations
            $table->index(['AC_NO', 'PART_NO'], 'idx_ac_part');
            $table->index(['AC_NO', 'WARD_NO'], 'idx_ac_ward');
            $table->index(['SEX', 'AGE'], 'idx_sex_age');
            $table->index(['SLNOINPART', 'PART_NO'], 'idx_slno_part');
            $table->index(['AC_NO', 'SEX'], 'idx_ac_sex');
            $table->index(['PART_NO', 'WARD_NO'], 'idx_part_ward');

            // Full-text search indexes for better text search performance
            $table->index(['FM_NAME_EN', 'LASTNAME_EN'], 'idx_full_name_en');
            $table->index(['FM_NAME_V1', 'LASTNAME_V1'], 'idx_full_name_v1');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_data');
    }
};

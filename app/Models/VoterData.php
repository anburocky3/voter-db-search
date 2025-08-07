<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoterData extends Model
{
    use HasFactory;

    protected $table = 'voter_data';

    protected $fillable = [
        'ST_CODE', 'AC_NO', 'PART_NO', 'SLNOINPART', 'HOUSE_NO_EN', 'SECTION_NO',
        'FM_NAME_EN', 'FM_NAME_V1', 'LASTNAME_EN', 'LASTNAME_V1', 'RLN_TYPE',
        'RLN_FM_NM_EN', 'RLN_FM_NM_V1', 'RLN_L_NM_EN', 'RLN_L_NM_V1', 'IDCARD_NO',
        'STATUSTYPE', 'PARTLINKNO', 'SEX', 'AGE', 'ORGNLISTNO', 'ORGN_TYPE',
        'CHNGLISTNO', 'CHNG_TYPE', 'WARD_NO', 'FIELD_3', 'DLT_REASON', 'OLD_HOUSENO',
        'ADDRESS1_EN', 'ADDRESS1_V1', 'ADDRESS2_EN', 'ADDRESS2_V1', 'ADDRESS3_EN', 'ADDRESS3_V1',
        'UIDNO', 'MOBILENO', 'ac_Name_en', 'ac_Name_ta', 'address', 'addressta'
    ];

    protected $casts = [
        'AC_NO' => 'integer',
        'PART_NO' => 'integer',
        'SLNOINPART' => 'integer',
        'SECTION_NO' => 'integer',
        'PARTLINKNO' => 'integer',
        'AGE' => 'integer',
        'ORGNLISTNO' => 'integer',
        'CHNGLISTNO' => 'integer',
        'WARD_NO' => 'integer',
        'UIDNO' => 'integer',
    ];
}

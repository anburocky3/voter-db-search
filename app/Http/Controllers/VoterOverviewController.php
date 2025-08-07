<?php

namespace App\Http\Controllers;

use App\Models\VoterData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VoterOverviewController extends Controller
{
    public function index(Request $request)
    {
        $acNo = $request->input('ac_no');
        $availableACs = VoterData::select('AC_NO')->distinct()->orderBy('AC_NO')->pluck('AC_NO');

        if ($acNo) {
            $query = VoterData::where('AC_NO', $acNo);

            $gender = $query->select('SEX', DB::raw('count(*) as total'))
                ->groupBy('SEX')->pluck('total', 'SEX');

            // Normalize gender data
            $male = $gender['M'] ?? 0;
            $female = $gender['F'] ?? 0;
            $others = $gender['O'] ?? 0;

            // foreach ($gender as $key => $value) {
            //     if (in_array(strtoupper($key), ['O', 'OTHERS', 'OTHER'])) {
            //         $others += $value;
            //     }
            // }
            $genderData = [
                'Male' => $male,
                'Female' => $female,
                'Others' => $others,
            ];

            $status = $query->select('STATUSTYPE', DB::raw('count(*) as total'))
                ->groupBy('STATUSTYPE')->pluck('total', 'STATUSTYPE');

            $ageGroups = [
                '18-25' => (clone $query)->whereBetween('AGE', [18, 25])->count(),
                '26-40' => (clone $query)->whereBetween('AGE', [26, 40])->count(),
                '41-60' => (clone $query)->whereBetween('AGE', [41, 60])->count(),
                '60+'   => (clone $query)->where('AGE', '>=', 61)->count(),
            ];

            $wardWise = $query->select('WARD_NO', DB::raw('count(*) as total'))
                ->groupBy('WARD_NO')->orderBy('WARD_NO')->pluck('total', 'WARD_NO');

            $acWise = $query->select('AC_NO', DB::raw('count(*) as total'))
                ->groupBy('AC_NO')->orderBy('AC_NO')->pluck('total', 'AC_NO');
        } else {
            $genderData = $status = $ageGroups = $wardWise = $acWise = null;
        }


        return view('dashboard.voter-overview', compact(
            'genderData', 'status', 'ageGroups', 'wardWise', 'acWise', 'availableACs', 'acNo'
        ));
    }
}

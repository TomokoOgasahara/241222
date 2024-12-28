<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompsDatabaseController extends Controller
{
    public function showCompanies(Request $request)
    {
        // 企業一覧を取得
        $companies = DB::table('compsdatabase')->select('comp_name')->distinct()->get();

        // 選択された企業のデータを取得
        $selectedCompany = null;
        $womensData = null;

        if ($request->has('comp_name')) {
            $selectedCompany = DB::table('compsdatabase')
                ->where('comp_name', $request->input('comp_name'))
                ->first();

            // womensdatabaseから関連データを取得
            $womensData = DB::table('womensdatabase')
                ->where('comp_name', $request->input('comp_name'))
                ->first();
        }

        return view('comps_database', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
            'womensData' => $womensData,
        ]);
    }
}


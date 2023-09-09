<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index() {
      return Inertia::render('Analysis');
    }

    public function rfm() {
      // RFM分析
      $startDate = '2023-7-26';
      $endDate = '2023-8-26';
      // 1.購入IDごとにまとめる
      $subQuery = Order::betweenDate($startDate,$endDate)
      ->groupBy('id')
      ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase, created_at');

      // 2.購入ごとにまとめて最終購入日、回数、合計金額を取得
      $subQuery = DB::table($subQuery)
      ->groupBy('customer_id')
      ->selectRaw('customer_id, customer_name, max(created_at) as recentDate, datediff(now(), max(created_at)) as recency, count(customer_id) as frequency, sum(totalPerPurchase) as monetary');

      // 3.RFMランクを仮設定
      // 4.会員ごとのRFMランクを計算
      $rfmPrms = [ 15, 30, 60, 120, 7, 5, 3, 2, 10000, 8000, 5000, 3000 ];
      $subQuery = DB::table($subQuery)
      ->selectRaw('customer_id, customer_name, recentDate, recency, frequency, monetary,
      case
        when recency < ? then 5
        when recency < ? then 4
        when recency < ? then 3
        when recency < ? then 2
        else 1 end as r,
      case
        when ? <= frequency then 5
        when ? <= frequency then 4
        when ? <= frequency then 3
        when ? <= frequency then 2
        else 1 end as f,
      case
        when ? <= monetary then 5
        when ? <= monetary then 4
        when ? <= monetary then 3
        when ? <= monetary then 2
        else 1 end as m', $rfmPrms);

        // dd($subQuery->get());

        // 5.ランク毎の数を計算
        $total = DB::table($subQuery)->count();

        $rCount = DB::table($subQuery)
        ->rightJoin('ranks', 'ranks.rank', '=', 'r')
        ->groupBy('rank')
        ->selectRaw('rank as r, count(r)')
        ->orderBy('r', 'desc')
        ->pluck('count(r)');

        $fCount = DB::table($subQuery)
        ->rightJoin('ranks', 'ranks.rank', '=', 'f')
        ->groupBy('rank')
        ->selectRaw('rank as f, count(f)')
        ->orderBy('f', 'desc')
        ->pluck('count(f)');
        
        $mCount = DB::table($subQuery)
        ->rightJoin('ranks', 'ranks.rank', '=', 'm')
        ->groupBy('rank')
        ->selectRaw('rank as m, count(m)')
        ->orderBy('m', 'desc')
        ->pluck('count(m)');

        $eachCount = [];
        $rank = 5;

        for ( $i = 0; $i < 5; $i++ ){
          array_push($eachCount,[
            'rank' => $rank, 
            'r' => $rCount[$i], 
            'f' => $fCount[$i], 
            'm' => $mCount[$i]
          ]);
          $rank--;
        }

        // dd($total, $eachCount, $rCount, $fCount, $mCount);
        // 6. RとFで2次元で表示を行う
        $data = DB::table($subQuery)
        ->rightJoin('ranks', 'ranks.rank', '=', 'r')
        ->groupBy('rank')
        ->selectRaw('concat("r_", rank) as rRank,
        count(case when f = 5 then 1 end) as f_5,
        count(case when f = 4 then 1 end) as f_4,
        count(case when f = 3 then 1 end) as f_3,
        count(case when f = 2 then 1 end) as f_2,
        count(case when f = 1 then 1 end) as f_1') 
        ->orderBy('rRank', 'desc')
        ->get();
    }
}

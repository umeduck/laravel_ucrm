<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class AnalysisService {

  public static function perDay ($subQuery) {

    $subQuery->where('status', true)
    ->groupBy('id')
    ->selectRaw('id, sum(subtotal) as totalPerPurchase,
    DATE_FORMAT(created_at, "%Y%m%d") as date');

    $data = DB::table($subQuery)
    ->groupBy('date')
    ->selectRaw('date, sum(totalPerPurchase) as total')
    ->get();

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

  public static function perMonth ($subQuery) {

    $subQuery->where('status', true)
    ->groupBy('id')
    ->selectRaw('id, sum(subtotal) as totalPerPurchase,
    DATE_FORMAT(created_at, "%Y%m") as date');

    $data = DB::table($subQuery)
    ->groupBy('date')
    ->selectRaw('date, sum(totalPerPurchase) as total')
    ->get();

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

  public static function perYear ($subQuery) {

    $subQuery->where('status', true)
    ->groupBy('id')
    ->selectRaw('id, sum(subtotal) as totalPerPurchase,
    DATE_FORMAT(created_at, "%Y") as date');

    $data = DB::table($subQuery)
    ->groupBy('date')
    ->selectRaw('date, sum(totalPerPurchase) as total')
    ->get();

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

}
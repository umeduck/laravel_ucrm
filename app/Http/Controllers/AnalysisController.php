<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index() {
      $startDate = '2022-07-01';
      $endDate = '2022-07-31';

      $subQuery = Order::betweenDate($startDate, $endDate)->groupBy('id')->selectRaw('id ,customer_id, customer_name, SUM(subtotal) as totalPerPurchase');

      $subQuery = DB::table($subQuery)
      ->groupBy('customer_id')
      ->selectRaw('customer_id, customer_name, SUM(totalPerPurchase) as total')
      ->orderBy('total', 'desc')->get();

      dd($subQuery);

      return Inertia::render('Analysis');
    }
}

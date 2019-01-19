<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Customer;
use App\Line;
use DB;

class OrderController extends Controller
{
    public function getOrders()
    {
      // clear orders
      Order::truncate();
      Line::truncate();

      $client = new \GuzzleHttp\Client();

      // get pages from shopify api
      $res = $client->get( config('app.shopify_url') . 'orders/count.json?status=any');
      $res = json_decode($res->getBody());
      $count = ceil($res->count / 50);

      for ($i = 1; $i <= $count; $i++) {
        // get orders per page shopify api
        $res = $client->get( config('app.shopify_url') . 'orders.json?status=any&page=' . $i);
        $res = json_decode($res->getBody());
        $orders = $res->orders;

        // create new orders
        foreach ($orders as $key => $order) {
          $orderObj = new Order();
          $orderObj->total_price = $order->total_price;
          $orderObj->fulfillment_status = $order->fulfillment_status;
          $orderObj->user_id = $order->customer->id;
          $orderObj->save();

          foreach ($order->line_items as $key => $line) {
            // code...
            $lineObj = new Line();
            $lineObj->variant_id = $line->variant_id;
            $lineObj->title = $line->title;
            $lineObj->variant_title = $line->name;
            $lineObj->total_price = $line->quantity * $line->price;
            $lineObj->product_id = $line->product_id;
            $lineObj->save();
          }

        }

      }

      // return orders count
      return 'Total orders in your store: ' . Order::count();
    }

    public function getAverage()
    {
      return round(Order::avg('total_price'), 2);
    }

    public function getAveragePerUser()
    {
      $orders = Order::selectRaw('format(AVG(total_price), 2) average, user_id')
      ->groupBy('user_id')
     ->orderByRaw('average ASC')
      ->get();

      foreach ($orders as $key => $order) {
        $orders[$key]['customer'] = Customer::where('id', $order->user_id)->first()->toArray();
      }

      return json_encode($orders);
    }

    public function getLineItems()
    {
      $lines = Line::selectRaw('format(AVG(total_price), 2) average, variant_id, variant_title')
      ->groupBy('variant_id', 'variant_title')
      ->get();

      return json_encode($lines);
    }

}

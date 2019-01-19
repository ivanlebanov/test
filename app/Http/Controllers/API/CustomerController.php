<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use DB;

class CustomerController extends Controller
{
    public function getCustomers()
    {
      // clear customer
      Customer::truncate();
      // get orders from shopify api
      $client = new \GuzzleHttp\Client();

      // get pages from shopify api
      $res = $client->get( config('app.shopify_url') . 'customers/count.json');
      $res = json_decode($res->getBody());
      $count = ceil($res->count / 50);

      for ($i = 1; $i <= $count; $i++) {

        $res = $client->get( config('app.shopify_url') .  'customers.json?page=' . $i);
        $res = json_decode($res->getBody());
        $customers = $res->customers;

        // create new customers
        foreach ($customers as $key => $customer) {
          $customerObj = new Customer();
          $customerObj->id = $customer->id;
          $customerObj->first_name = $customer->first_name;
          $customerObj->last_name = $customer->last_name;
          $customerObj->email = $customer->email;
          $customerObj->save();
        }

      }

      // return $customers count
      return 'Total customers in your store: ' . Customer::count();
    }



}

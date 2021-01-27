<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Jobs\SendOrderEmail;

class MailController extends Controller
{
    public function index() {
        for ($i=0; $i<20; $i++) {

            $order = Order::findOrFail( rand(1,50) );
            SendOrderEmail::dispatch($order)->onQueue('email');

        }

        return 'Dispatched orders';
    }
}

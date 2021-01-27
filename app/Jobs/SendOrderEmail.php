<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Redis::throttle('any_key')->allow(2)->every(1)->then(function () {

            $recipient = 'ma.syaputra21@gmail.com';
            Mail::to($recipient)->send(new OrderShipped($this->order));
            Log::info('Emailed order ' . $this->order->id);

        }, function () {
            return $this->release(2);
        });
    }
}

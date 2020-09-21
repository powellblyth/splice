<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\UnprocessedOrdersNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class reportUnprocessedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'core:reportunprocessedorders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reports orders that appear not to have been processed yet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reportItems = DB::select("select order_number, orders.order_date from order_lines 

LEFT JOIN orders on order_lines.order_id = orders.id

where order_lines.id NOT IN (select order_line_id from order_line_shipment)
AND order_lines.id > 6069
AND order_lines.type = 'product'

AND orders.source_status = 'Placed'
AND orders.is_test = 0
AND orders.order_date > '2019-02-14'
");//
        // NOTE the date, before this all orders are fake or never to be processed


        if (0 < count($reportItems)){
            $notifiableUsers = User::Where('notify_about_failed_orders', true)->get();
                $notification = new UnprocessedOrdersNotification($reportItems);
                foreach ($notifiableUsers as $user) {
                    try {
                        $user->notify($notification);
                    } catch (\Aws\Ses\Exception\SesException $e) {
                        ;
                    }
            }
        }
    }
}

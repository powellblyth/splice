<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class SearchOrders extends Component
{
    public        $search = '';
    public ?Order $highlightedOrder;

    public function __construct($id = null)
    {
        $this->highlightedOrder = null;
        parent::__construct($id);
    }

    public function showOrder(Order $order)
    {
        $this->highlightedOrder = $order;
//        return view('livewire.order-details');
    }

    public function render()
    {
        $orders = Order::with(['order_lines', 'order_lines.shipments'])
            ->where('order_number', 'like', '%'.$this->search . '%')
            ->orderBy('order_date', 'desc')
            ->paginate(50);
        if (count($orders) === 1) {
            $this->highlightedOrder = $orders->first();
        }
        return view('livewire.search-orders', [
            'orders' =>$orders,
        ]);
    }
}

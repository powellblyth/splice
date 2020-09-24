<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderDetails extends Component
{
    public Order $order;

    public function render()
    {
        return view('livewire.order-details');
    }
}

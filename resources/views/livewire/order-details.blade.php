
<div>
    <input wire:model="search" type="text" placeholder="Search orders..."/>

@foreach($orders as $order)
    <h2>{{ $order->order_number }}</h2>
        Status: {{$order->status}}
    <p>@foreach($order->order_lines as $line)
    {{$line->quantity}} x {{$line->product_code}} @ {{$line->unit_price}} = {{$line->total_price}}
            <livewire:order-details>
        @endforeach
    </p>
    @endforeach
    </div>
<div class="card" style="margin-left:16px; padding:4px; border:1px solid grey; background-color:#ffff99">

    <h2>{{ $this->order->order_number }}</h2>
    <span class="card-title" style="font-weight: bold">Local status:</span> <span
        class="color:darkblue">{{$this->order->status}}</span><br/>
    <span class="card-title" style="font-weight: bold">{{$this->order->source}} Status:</span> {{$this->order->source_status}}<br/>
    <span class="card-title" style="font-weight: bold">Created</span> {{$this->order->order_date}}<br/>
    <span class="card-title" style="font-weight: bold">Delivery Address</span><br/>
    <div style="display: block;margin-left:20px">{{$this->order->customer_name}}<br/>
        {!! $this->order->formattedAddress('<br />')!!}</div>
    <span class="card-title" style="font-weight: bold">Telephone: </span>{{ $this->order->customer_telephone}}<br/>
    <span class="card-title" style="font-weight: bold">Email:</span> {{ $this->order->customer_email}}<br/>
    <hr/>
    <span class="card-title" style="font-weight: bold">Items:</span>
    <div style="display: block;margin-left:20px;margin-bottom:20px">
        @foreach($order->order_lines as $line)

            {{$line->product_description}} ({{$line->product_code}}) {{$line->quantity}} @
            &pound;{{$line->unit_price}} = &pound;{{$line->total_price}} (<i>{{$line->source_status}})</i><br/>
            {{--            <livewire:order-details>--}}

            @if(count($line->shipments) > 0)
                <span class="card-title" style="font-weight: bold">Shipments:</span><br />
                <div style="display: block;margin-left:20px;margin-bottom:20px">
                    @foreach($line->shipments as $shipment)
                        {{$shipment->sku}} x {{$shipment->quantity}} with {{$shipment->carrier}} [{{$shipment->tracking_number}}] created {{$shipment->created_at->format('Y-m-d H')}}
                    @endforeach
                </div>
            @endif


        @endforeach
    </div>
    <span class="card-title" style="font-weight: bold">Total:</span> &pound;{{$this->order->total}}<br/>
    <span class="card-title" style="font-weight: bold">Tax:</span> &pound;{{$this->order->tax_total}}<br/>
    <span class="card-title" style="font-weight: bold">Weight:</span> {{$this->order->weight}}<br/>
    <span class="card-title" style="font-weight: bold">Warehouse:</span> {{$this->order->warehouse}}<br/>

</div>
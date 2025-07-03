@extends('user.layouts.master')
@inject("currencyService", "App\Services\CurrencyService")
@php
    $currency = session("currency", "NGN");
@endphp

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Order       <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <tr>
                <th>Order No.</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Shipping Fee</th>
                <th>Amount</th>
                <th>Total Amount</th>
                <th>Status</th>
                {{-- <th>Action</th> --}}
            </tr>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{ $order->order_number }}</td>
            <td>
                @foreach($order->orderItems as $item)
                    {{ $item->name }} <br>
                @endforeach
            </td>
            <td>
                @foreach ($order->orderItems as $item)
                    {{ $item->quantity }}<br>
                @endforeach
            </td>
            <td>{{ $currencyService->convert($order->shipping_fee, $currency) }}</td>
            <td>
                @php
                    $items = $order->orderItems;
                @endphp

                @if ($items->count() === 1)
                    {{ $currencyService->convert($items->first()->price, $currency) }}
                @else
                    {!! $items->map(fn($item) => $currencyService->convert($item->price, $currency) . " x " . $item->quantity)->implode("<br>") !!}
                @endif
            </td>
            <td>{{ $currencyService->convert($order->total, $currency) }}</td>
            <td>
                @if ($order->status == "new")
                    <span class="badge badge-primary">{{ $order->status }}</span>
                @elseif($order->status == "process")
                    <span class="badge badge-warning">{{ $order->status }}</span>
                @elseif($order->status == "delivered")
                    <span class="badge badge-success">{{ $order->status }}</span>
                @else
                    <span class="badge badge-danger">{{ $order->status }}</span>
                @endif
            </td>
            {{-- <td>
                <a href="{{ route("order.edit", $order->id) }}" class="btn btn-primary btn-sm"
                    data-toggle="tooltip" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form method="POST" action="{{ route("order.destroy", $order->id) }}"
                    style="display:inline;">
                    @csrf
                    @method("delete")
                    <button class="btn btn-danger btn-sm dltBtn" data-id="{{ $order->id }}"
                        data-toggle="tooltip" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td> --}}
        </tr>
    </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">ORDER INFORMATION</h4>
              <table class="table">
                <tr>
                    <td>Order Number</td>
                    <td>: {{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td>: {{ $order->created_at->format('D d M, Y \a\t g:i a') }}</td>
                </tr>
                <tr>
                    <td>Quantity</td>
                    <td>
                        @foreach ($order->orderItems as $item)
                           {{ $item->name }} : {{ $item->quantity }}<br>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td>Order Status</td>
                    <td>: {{ ucfirst($order->status) }}</td>
                </tr>
                <tr>
                    <td>Shipping Fee</td>
                    <td>: {{ $currencyService->convert($order->shipping_fee, $currency) }}</td>
                </tr>
                <tr>
                    <td>Amount</td>
                    <td>:
                        @php
                            $productTotal = $order->orderItems->sum(function ($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        {{ $currencyService->convert($productTotal, $currency) }}
                    </td>
                </tr>

                @if (isset($order->coupon))
                    <tr>
                        <td>Coupon</td>
                        <td>: ${{ number_format($order->coupon, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Total Amount</td>
                    <td>: {{ $currencyService->convert($order->total, $currency) }}</td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>:
                        @if ($order->payment_method == "cash_on_delivery")
                            Cash on Delivery
                        @elseif($order->payment_method == "paystack")
                            Paystack
                        @elseif($order->payment_method == "stripe")
                            Stripe
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td>: {{ ucfirst($order->payment_status) }}</td>
                </tr>
            </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">SHIPPING INFORMATION</h4>
              <table class="table">
                <tr>
                    <td>Full Name</td>
                    <td>: {{ $order->first_name }} {{ $order->last_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: {{ $order->email }}</td>
                </tr>
                <tr>
                    <td>Phone No.</td>
                    <td>: {{ $order->phone }}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>: {{ $order->address }}</td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>: {{ $order->country }}</td>
                </tr>
                <tr>
                    <td>Post Code</td>
                    <td>: {{ $order->post_code }}</td>
                </tr>
            </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush

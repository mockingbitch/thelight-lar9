@php
    use App\Constants\OrderConstant;
@endphp
@extends('layouts.homeLayout')
@section('content')
<div id="breadcrumb" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-xs-9 col-md-9">
                <ul class="breadcrumb-tree">
                    <li><a href="{{route('home')}}">Trang chủ</a></li>
                    <li><a href="{{route('home.table')}}">Bàn</a></li>
                    <li class="active">Chi tiết bàn</li>
                </ul>
            </div>
            <div class="col-xs-3 col-md-3">
                <a href={{route('home.table')}} class="btn btn-primary">Trở về</a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /BREADCRUMB -->
<div class="shopping-cart section">
    @if (null !== $order && null !== $order->orderDetails && ! empty($order->orderDetails->toArray()))
        <div class="container">
            <div class="row">
                <h2 style="margin-left: 20px">{{$table->name}}</h2>
                <hr>
                <div class="col-12" id="list-cart">
                    <!-- Shopping Summery -->
                    <table class="table shopping-summery cart-change">
                        <thead>
                        <tr class="main-hading">
                            <th></th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Tổng</th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $item)
                                <tr>
                                    <td class="image" data-title="No"><img width="50px" src="{{asset('upload/images/products/' . $item->product->image)}}" alt="#"></td>
                                    <td class="product-des" data-title="Description">
                                        <p class="product-name"><a href="#">{{$item->product->name}}</a></p>
                                    </td>
                                    <td align="center" class="price" data-title="Price"><span>{{number_format($item->price)}}</span></td>
                                    <td class="qty" data-title="Qty" align="center"><!-- Input Order -->
                                        {{-- <div class="input-group sm">
                                            <input onchange="" id="" type="number" name="quant[1]" class="input-number sm" min="1" value="">
                                        </div> --}}
                                        {{$item->quantity}}
                                        <!--/ End Input Order -->
                                    </td>
                                    <td align="center" class="total-amount" data-title="Total">{{number_format($item->total)}}<span>
                                    <td align="center" class="total-amount" data-title="Total">
                                        @switch($item->status)
                                            @case(OrderConstant::STATUS_PENDING)
                                                Đang chờ
                                                @break
                                            @case(OrderConstant::STATUS_DONE)
                                                Đã xong
                                                @break
                                            @case(OrderConstant::STATUS_DELIVERED)
                                                Đã lên đồ
                                                @break
                                            @case(OrderConstant::STATUS_CANCEL)
                                                Hủy
                                                @break
                                            @default
                                                
                                        @endswitch
                                    <span>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                <!--/ End Shopping Summery -->
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <!-- Total Amount -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-4 col-md-7 col-12 col-xs-12" id="sub" >
                                <div class="right sub-change">
                                    <div class="total" align="right" style="margin: 20px">
                                        <span style="font-size: 30px">Tổng:</span> &emsp; &emsp; <span style="color: red;font-size: 30px;font-weight: bold">{{number_format($order->total)}}</span>
                                    </div>
                                    <hr />
                                    <div class="button5" style="margin: 20px">
                                        <div class="row" align="right">
                                            <button style="margin-right: 20px" class="btn btn-default col-xs-5" onclick="handleOrder({{$table->id}})">Order</button>
                                            <button class="btn btn-danger col-md-5" onclick="handleCheckOut({{$table->id}})">Thanh toán</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ End Total Amount -->
                </div>
            </div>
        </div>
        <div class="space-footer">
            <br>
            <br>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-12" id="list-cart" align="center">
                    <h3>Bàn {{$table->name}} trống</h3>
                    <button style="margin: 0px auto;" class="btn btn-default" onclick="handleOrder({{$table->id}})"><h4>Order</h4></button>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    function handleOrder(id) {
        var url = '{{ route("home.order") }}?table=' + id;
        location.replace(url);
    }

    function handleCheckOut(id) {
        var url = '{{ route("home.order.checkout", ":id") }}';
        url = url.replace(':id', id);
        // location.replace(url);
        Swal.fire({
            title: 'Thanh toán',
            text: "Xác nhận thanh toán đơn hàng!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận!',
            cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get(url, function (data) {
                        $('.order-icon').load(`${urlOrder} .order-icon`);
                        Swal.fire(
                            'Thành công!',
                            'Thanh toán thành công',
                            'success'
                            )
                    })
                }
            })
    }
</script>
@endsection
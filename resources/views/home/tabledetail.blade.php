@php
use App\Constants\OrderConstant;
use App\Constants\RouteConstant;
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
                    <li><a href="{{route(RouteConstant::HOMEPAGE)}}">Trang chủ</a></li>
                    <li><a href="{{route(RouteConstant::HOME['table_list'])}}">Bàn</a></li>
                    <li class="active">Chi tiết bàn</li>
                </ul>
            </div>
            <div class="col-xs-3 col-md-3">
                <a href={{route(RouteConstant::HOME['table_list'])}} class="btn btn-primary">Trở về</a>
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
                <div class="col-xs-9 col-md-9">
                    <h2>{{$table->name}}</h2>
                </div>
                <div class="col-xs-2 col-md-2">
                    <a href={{route(RouteConstant::HOME['table_list'])}} class="btn btn-danger">Hủy đơn</a>
                </div>
                <hr>
                <div class="col-12" id="list-order">
                    <!-- Shopping Summery -->
                    <table class="table shopping-summery order-change">
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
                                <tr onclick="handleClickItemOrder({{$item->id}}, '{{$item->product->name}}', '{{$item->status}}')">
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
                                    <td align="center" class="total-amount" data-title="status">
                                        @switch($item->status)
                                            @case(OrderConstant::STATUS_PENDING)
                                                <i class="fa-solid fa-hourglass-half fa-spin"></i>
                                                @break
                                            @case(OrderConstant::STATUS_DONE)
                                                <i class="fa-regular fa-circle-check fa-bounce" style="color: green"></i>
                                                @break
                                            @case(OrderConstant::STATUS_DELIVERED)
                                                <i class="fa-sharp fa-solid fa-circle-check" style="color: green"></i>
                                                @break
                                            @case(OrderConstant::STATUS_CANCEL)
                                                <i class="fa-sharp fa-solid fa-circle-exclamation" style="color: red"></i>
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
    function handleClickItemOrder(id, product_name, status) {
        var current_url = '{{ route("home.table.detail", ":id") }}';
        current_url = current_url.replace(':id', {{$table->id}});

        if (status == 'PENDING') {
            Swal.fire({
            title: 'Cập nhật ' + product_name,
            html: `<input type="text" id="quantity" class="swal2-input" placeholder="Số lượng">`,
            input: 'select',
            inputOptions: {
                'PENDING': 'Đang chờ',
                'DONE': 'Đã xong đồ',
                'DELIVERED': 'Đã lên đồ',
                'CANCEL': 'Hủy'
            },
            confirmButtonText: 'Xác nhận',
            focusConfirm: false,
                preConfirm: () => {
                    const quantity = Swal.getPopup().querySelector('#quantity').value
                    const status = Swal.getPopup().querySelector('.swal2-select').value
                    if (quantity && (isNaN(quantity) || quantity <= 0)) {
                        Swal.showValidationMessage(`Vui lòng nhập số lượng`)
                    }
                    return { quantity: quantity, status: status }
                }
            }).then((result) => {
                var quantity = parseInt(result.value.quantity);
                var status = result.value.status;

                Swal.fire(`
                    Số lượng: ${result.value.quantity}
                `.trim())
                $.get('{{route('home.order.update')}}', {'id': id, 'quantity': quantity, 'status': status}, function (data) {
                    if (data == 1) {
                        console.log(current_url);
                        $('.order-change').load(`${current_url} .order-change`)
                        $('.total').load(`${current_url} .total`);
                        Swal.fire(
                            'Thành công!',
                            'Cập nhật thành công',
                            'success'
                        );
                    } else {
                        Swal.fire(
                        'Đã có lỗi xảy ra!',
                        'Cập nhật không thành công',
                        'warning'
                        );
                    }
                })
            })
        }
        if (status == 'DONE') {
            Swal.fire({
            title: 'Cập nhật ' + product_name,
            input: 'select',
            inputOptions: {
                'DONE': 'Đã xong đồ',
                'DELIVERED': 'Đã lên đồ'
            },
            confirmButtonText: 'Xác nhận',
            focusConfirm: false,
                preConfirm: () => {
                    const status = Swal.getPopup().querySelector('.swal2-select').value
                    return { status: status }
                }
            }).then((result) => {
                var status = result.value.status;

                $.get('{{route('home.order.update')}}', {'id': id, 'status': status}, function (data) {
                    if (data == 1) {
                        console.log(current_url);
                        $('.order-change').load(`${current_url} .order-change`)
                        $('.total').load(`${current_url} .total`);
                        Swal.fire(
                            'Thành công!',
                            'Cập nhật thành công',
                            'success'
                        );
                    } else {
                        Swal.fire(
                        'Đã có lỗi xảy ra!',
                        'Cập nhật không thành công',
                        'warning'
                        );
                    }
                })
            })
        }
    }

    function handleOrder(id) {
        var url = '{{ route("home.order.products") }}?table=' + id;
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
                        console.log(data);
                        if (data == 1) {
                            Swal.fire({
                                title: 'Thanh toán thành công',
                                text: "Đã thanh toán!",
                                icon: 'success',
                                // showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok!',
                                // cancelButtonText: 'Hủy'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire(
                            'Đã có lỗi xảy ra!',
                            'Thanh toán không thành công',
                            'warning'
                            );
                        }
                    })
                }
            })
    }
</script>

@endsection
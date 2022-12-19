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
            <div class="col-xs-9 col-9">
                <ul class="breadcrumb-tree">
                    <li><a href="{{route(RouteConstant::HOMEPAGE)}}">Trang chủ</a></li>
                    <li><a href="{{route(RouteConstant::HOME['table_list'])}}">Bàn</a></li>
                    <li class="active">Chi tiết bàn</li>
                </ul>
            </div>
            <div class="col-xs-3 col-3">
                <a href={{ url()->previous()}} class="btn btn-primary" style="float: right">Trở về</a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /BREADCRUMB -->
<div class="shopping-cart section">
    @if (null !== $order && null !== $order->orderDetails && ! empty($order->orderDetails->toArray()))
    <div class="container container-fluid py-4">
        <div class="row">
            <div class="col-12 column-margin">
                <div class="card mb-4">
                    <div class="card-header pb-0 row">
                        <div class="col-xs-9 col-9">
                            <h2>{{$table->name}}</h2>
                        </div>
                        <div class="col-3">
                            <a onclick="handleDeleteOrder({{$order->id}}, {{$table->id}})" class="btn btn-danger" style="float: right">Hủy đơn</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 order-change">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên sản phẩm</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Đơn giá</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Số lượng</th>
                                        {{-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tổng</th> --}}
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails as $item)
                                        <tr onclick="handleClickItemOrder({{$item->id}}, '{{$item->product->name}}', '{{$item->status}}')">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="{{asset('upload/images/products/' . $item->product->image)}}" class="avatar avatar-sm me-3" alt="{{$item->product->name}}">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h4 class="mb-0 text-sm">{{$item->product->name}}</h4>
                                                        {{-- <p class="text-xs text-secondary mb-0">john@creative-tim.com</p> --}}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{number_format($item->price)}}</p>
                                                {{-- <p class="text-xs text-secondary mb-0">Organization</p> --}}
                                            </td>
                                            {{-- <td>
                                                <p class="text-xs font-weight-bold mb-0">{{number_format($item->total)}}</p>
                                            </td> --}}
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{$item->quantity}}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @switch($item->status)
                                                        @case(OrderConstant::STATUS_PENDING)
                                                            <span class="badge badge-sm bg-gradient-warning">
                                                                <i class="fa-solid fa-hourglass-half fa-spin" style="color: grey"></i>
                                                                Đang chờ
                                                            </span>
                                                            @break
                                                        @case(OrderConstant::STATUS_DONE)
                                                            <span class="badge badge-sm bg-gradient-success">
                                                                <i class="fa-regular fa-circle-check fa-bounce" style="color: white"></i>
                                                                Đã xong
                                                            </span>
                                                            @break
                                                        @case(OrderConstant::STATUS_DELIVERED)
                                                            <span class="badge badge-sm bg-gradient-info">
                                                                <i class="fa-sharp fa-solid fa-circle-check" style="color: white"></i>
                                                                Đã lên đồ
                                                            </span>
                                                            @break
                                                        @case(OrderConstant::STATUS_CANCEL)
                                                            <span class="badge badge-sm bg-gradient-danger">
                                                                <i class="fa-sharp fa-solid fa-circle-exclamation" style="color: red"></i>
                                                                Đã hủy
                                                            </span>
                                                            @break
                                                        @default
                                                    @endswitch
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-secondary text-xs font-weight-bold">{!! $item->note !!}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <!-- Total Amount -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-12 col-xs-12" id="sub" >
                                <div class="right sub-change">
                                    <div class="total" align="right" style="margin: 20px">
                                        <span style="font-size: 30px">Tổng:</span> &emsp; &emsp; <span style="color: red;font-size: 30px;font-weight: bold">{{number_format($order->total)}}</span>
                                    </div>
                                    <hr />
                                    <div class="button5" style="margin: 20px">
                                        <div class="row" align="right">
                                            <button style="margin-right: 20px" class="btn btn-default col-5" onclick="handleOrder({{$table->id}})">Order</button>
                                            <button class="btn btn-danger col-5" onclick="handleCheckOut({{$table->id}})">Thanh toán</button>
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
        let url = '{{ route("home.order.products") }}?table=' + id;
        location.replace(url);
    }

    function handleCheckOut(id) {
        let url = '{{ route("home.order.checkout", ":id") }}';
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

    function handleDeleteOrder(order_id, table_id) {
        let url = '{{ route("home.table.detail", ":id") }}';
        url = url.replace(':id', table_id);

        Swal.fire({
            title: 'Xóa order',
            text: "Chắc chắn xóa mục này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.get("{{route('home.order.delete')}}", {"order": order_id, "table": table_id}, function(data) {
                    if (data == 1) {
                        Swal.fire(
                        'Đã xóa!',
                        'Order đã được xóa',
                        'success'
                        )
                        setTimeout(() => {
                            location.replace(url);
                        }, 1000);
                    } else {
                        Swal.fire(
                        'Đã xảy ra lỗi!',
                        'Order chưa được xóa',
                        'warning'
                        )
                    }
                });
            }
        })
    }

    $(document).ready(function () {
        let errCode = '{{$orderErrCode}}';
        let errMsg = '{{$orderErrMsg}}';
        console.log(errCode, errMsg);
        if (errCode && errCode === '0' && errMsg) {
            Swal.fire(
                'Xóa thành công!',
                errMsg,
                'warning'
                );
        }
        if (errCode && errCode === '1' && errMsg) {
            Swal.fire(
                'Đã có lỗi xảy ra!',
                errMsg,
                'warning'
                );
        }
    });
</script>

@endsection
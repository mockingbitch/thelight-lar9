@php
use App\Constants\RouteConstant;
use App\Constants\OrderConstant;
@endphp

@extends('layouts.homeLayout')
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card mb-4">
            <div class="card-header pb-0 mt-3 mb-4">
                <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách order</h3>
            </div>

            <div class="card-body px-0 pt-0 pb-2 mt-4">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Bàn</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Trạng thái</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Nhân viên</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b></b></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr class="tb-row">
                                    <td>
                                        <div class="d-flex px-2 py-1 margin-tbl-item">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0">{{$item['name']}}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if (null !== $item['order'])
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">{{$item['percent']}}%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-{{$item['percent'] == 100 ? 'success' : 'warning'}}" role="progressbar" aria-valuenow="{{$item['percent']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$item['percent']}}%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center margin-tbl-item">
                                                <span class="me-2 text-xs font-weight-bold">Bàn trống</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (null !== $item['order'])
                                            <div class="d-flex align-items-center justify-content-center margin-tbl-item">
                                                <span class="me-2 text-xs font-weight-bold">
                                                    {{$item['order']->waiter->name}}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (null !== $item['order'])
                                            <div class="d-flex align-items-center justify-content-center margin-tbl-item">
                                                <span class="me-2 text-xs font-weight-bold">
                                                    <a class="" data-toggle="modal" data-target="#tableModal_{{$item['id']}}">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        @foreach ($data as $item)
            <div class="modal fade" id="tableModal_{{$item['id']}}" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title text-center">{{$item['name']}}</h3>
                        </div>
                        @if (null !== $item['order'])
                            <div class="modal-body">
                                <div class="card-body px-0 pt-0 pb-2 mt-4">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            {{-- <thead>
                                            </thead> --}}
                                            <tbody>
                                                @foreach($item['order']->orderDetails as $detail)
                                                <tr>
                                                    <td class="image" data-title="No"><img width="50px" src="{{asset('upload/images/products/' . $detail->product->image)}}" alt="#"></td>
                                                    <td class="product-des" data-title="Description">
                                                        <p class="product-name"><a href="#">{{$detail->product->name}}</a></p>
                                                    </td>
                                                    <td align="center" class="price" data-title="Price"><span>{{number_format($detail->price)}}</span></td>
                                                    <td class="qty" data-title="Qty" align="center"><!-- Input Order -->
                                                        {{$detail->quantity}}
                                                    </td>
                                                    <td align="center" class="total-amount" data-title="Total">{{number_format($detail->total)}}<span>
                                                    <td align="center" class="total-amount" data-title="status">
                                                        @switch($detail->status)
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
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    // function handleClickRow(id) {
    //     var url = '{{ route("dashboard.category.update", ":id") }}';
    //     url = url.replace(':id', id);
    //     location.replace(url);
    // }

    $(document).ready(function() {
        // if (errCode && errCode === '1' && errMsg) {
        //     swal({
        //         title: "Thành công!",
        //         text: errMsg,
        //         icon: "success",
        //         button: "Đóng!",
        //         });
        // }
        // if (errCode && errCode === '0' && errMsg) {
        //     swal({
        //         title: "Thất bại!",
        //         text: errMsg,
        //         icon: "warning",
        //         button: "Đóng!",
        //         });
        // }

        $.get("{{route('home.order.list')}}", {"id": id}, function(data) {
            $('.total').load('{{route('home.order.list')}} .total');
        });
    });
</script>
@endsection
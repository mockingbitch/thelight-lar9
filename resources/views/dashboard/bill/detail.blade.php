@php
use App\Constants\RouteConstant;
@endphp

@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Chi tiết hóa đơn</h3>
        <a href="{{route(RouteConstant::DASHBOARD['bill_list'])}}" class="btn btn-secondary">Trở về</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><b>Sản phẩm</b></th>
                <th style="text-align: center"><b>Số lượng</b></th>
                <th style="text-align: center"><b>Đơn giá</b></th>
                <th style="text-align: center"><b>Tổng</b></th>
            </tr>
            </thead>
            <tbody>
            @php
                $subTotal = 0;
            @endphp
            @foreach($billDetails as $item)
            <tr class="tb-row" onclick="handleClickRow({{$item->id}})">
                <td>{{$item->product_name}}</td>
                <td style="text-align: center">{{$item->quantity}}</td>
                <td style="text-align: center">{{number_format($item->price)}}</td>
                <td style="text-align: center">{{number_format($item->total)}}</td>
            </tr>
            @php
                $subTotal += $item->total;
            @endphp
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align: center"><b>Thành tiền:</b></td>
                <td style="text-align: center"><b>{{number_format($subTotal)}}</b></td>
            </tr>
            </tbody>
        </table>

    </div>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
</script>
@endsection
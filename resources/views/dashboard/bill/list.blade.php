@php
use App\Constants\RouteConstant;
@endphp

@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Hóa đơn</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><b>Nhân viên</b></th>
                <th style="text-align: center"><b>Bàn</b></th>
                <th style="text-align: center"><b>Tổng</b></th>
                <th style="text-align: center"><b>Ngày</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($bills as $item)
            <tr class="tb-row" onclick="handleClickRow({{$item->id}})">
                <td>{{$item->waiter->name}}</td>
                <td style="text-align: center">{{$item->table->name}}</td>
                <td style="text-align: center">{{number_format($item->total)}}</td>
                <td style="text-align: center">{{$item->created_at}}</td>
                {{-- <td style="text-align: center">
                    @if ($item->status == 1)
                        <i class="far fa-thumbs-up" style="color:green"></i>
                    @else
                        <i class="far fa-thumbs-down" style="color:red"></i>
                    @endif
                </td> --}}
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    function handleClickRow(id) {
        var url = '{{ route("dashboard.bill.detail", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }

    $(document).ready(function() {
        // var errCode = $('.err-code').val();

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
    });
</script>
@endsection
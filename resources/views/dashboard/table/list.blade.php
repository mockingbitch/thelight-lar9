@php
use App\Constants\RouteConstant;
@endphp

@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách bàn</h3>
        <a href="{{route(RouteConstant::DASHBOARD['table_create'])}}">
            <button class="btn btn-primary" style="float: right;">Thêm mới</button>
        </a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><b>Tên bàn</b></th>
                <th style="text-align: center"><b>Mô tả</b></th>
                <th style="text-align: center"><b>Trạng thái</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tables as $item)
            <tr class="tb-row" onclick="handleClickRow({{$item->id}})">
                <td>{{$item->name}}</td>
                <td style="text-align: center">{{$item->description}}</td>
                <td style="text-align: center">
                    @if ($item->status == 1)
                        <i class="far fa-thumbs-up" style="color:green"></i>
                    @else
                        <i class="far fa-thumbs-down" style="color:red"></i>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    function handleClickRow(id) {
        var url = '{{ route("dashboard.table.update", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }

    $(document).ready(function() {
        let errCode = '{{$tableErrCode}}';
        let errMsg = '{{$tableErrMsg}}';

        if (errCode && errCode === '1' && errMsg) {
            swal({
                title: "Thành công!",
                text: errMsg,
                icon: "success",
                button: "Đóng!",
                });
        }
        if (errCode && errCode === '0' && errMsg) {
            swal({
                title: "Thất bại!",
                text: errMsg,
                icon: "warning",
                button: "Đóng!",
                });
        }
    });
</script>
@endsection
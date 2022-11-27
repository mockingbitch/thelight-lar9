@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách sản phẩm</h3>
        <a href="{{route('dashboard.product.create')}}">
            <button class="btn btn-primary" style="float: right;">Thêm mới</button>
        </a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><b>Tên sản phẩm</b></th>
                <th style="text-align: center"><b>Giá</b></th>
                <th style="text-align: center"><b>Danh mục</b></th>
                <th style="text-align: center"><b>Hình ảnh</b></th>
                <th style="text-align: center"><b>Trạng thái</b></th>
                <th style="text-align: center"><b>Xoá</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $item)
            <tr class="tb-row" onclick="handleClickRow({{$item->id}})">
                <td>{{$item->name}}</td>
                <td style="text-align: center">{{number_format($item->price)}}</td>
                <td style="text-align: center">{{$item->category->name}}</td>
                <td style="text-align: center"><img width="100px" src="{{asset('upload/images/products/' . $item->image)}}" alt="Product Image" /></td>
                <td style="text-align: center">
                    <span class="badge badge-sm {{$item->status == '1' ? 'bg-gradient-success' : 'bg-gradient-secondary'}}">
                        {{$item->status == '1' ? 'Hiển thị' : 'Ẩn'}}
                    </span>
                </td>
                <td align="left" style="text-align: center">
                    <a class="btn btn-danger"
                        onclick="confirmDelete({{$item->id}})">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    <br>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    function handleClickRow(id) {
        var url = '{{ route("dashboard.product.update", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }

    function confirmDelete(id) {
        swal({
            title: "Bạn có muốn xoá mục này?",
            text: "Dữ liệu xoá sẽ không thể khôi phục!",
            icon: "warning",
            buttons: [
                'Huỷ',
                'Xoá'
            ],
            dangerMode: true,
            }).then(function(isConfirm) {
            if (isConfirm) {
                $.get("{{route('dashboard.product.delete')}}", {"id": id}, function(data) {
                     $(".table").load("{{ route('dashboard.product.list') }} .table");
                });

                swal({
                title: 'Đã xoá!',
                text: 'Xoá thành công mục này!',
                icon: 'success'
                }).then(function() {
                });
            } else {
                swal("Huỷ", "Dữ liệu của bạn vẫn an toàn :)", "error");
            }
        })
    }

    $(document).ready(function() {
        // var errCode = $('.err-code').val();
        let errCode = '{{$productErrCode}}';
        let errMsg = '{{$productErrMsg}}';

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
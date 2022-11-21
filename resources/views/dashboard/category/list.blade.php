@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách chức vụ</h3>
        <a href="{{route('dashboard.category.create')}}">
            <button class="btn btn-primary" style="float: right;">Thêm mới</button>
        </a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><b>Tên danh mục</b></th>
                <th style="text-align: center"><b>Mô tả</b></th>
                <th style="text-align: center"><b>Trạng thái</b></th>
                <th style="text-align: center"><b>Xoá</b></th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $item)
            <tr class="tb-row" onclick="handleClickRow({{$item->id}})">
                <td>{{$item->name}}</td>
                <td style="text-align: center">{{$item->description}}</td>
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
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    function handleClickRow(id) {
        
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
                $.get("{{route('dashboard.category.delete')}}", {"id": id}, function(data) {
                     $(".table").load("{{ route('dashboard.category.list') }} .table");
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
        let errCode = {{$categoryErrCode}};
        let errMsg = '{{$categoryErrMsg}}';
console.log(errCode);
        if (errCode && errCode === 1 && errMsg) {
            swal({
                title: "Thành công!",
                text: errMsg,
                icon: "success",
                button: "Đóng!",
                });
        }
        if (errCode && errCode === 0 && errMsg) {
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
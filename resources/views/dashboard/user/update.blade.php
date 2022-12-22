@php
use App\Constants\RouteConstant;
@endphp

@extends('layouts.dashboardLayout')
@section('content')
<h2>Cập nhật người dùng</h2>
<a href="{{route(RouteConstant::DASHBOARD['user_list'])}}" class="btn btn-secondary">Trở về</a>

<div class="card mb-4">
    <form class="mx-4 pt-4" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="inputName">Tên người dùng @if ($errors->has('name'))<p class="text-error">*{{$errors->first('name')}}</p>@endif</label>
                <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp" value="{{$user->name}}">
            </div>
            <div class="form-group mt-4 col-6">
                <label for="inputEmail">Email</label>
                <input type="text" name="description" class="form-control" id="inputEmail" aria-describedby="nameHelp" value="{{$user->email}}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="inputPhone">Số điện thoại @if ($errors->has('phone'))<p class="text-error">*{{$errors->first('phone')}}</p>@endif</label>
                <input type="text" name="phone" class="form-control" id="inputPhone" aria-describedby="nameHelp" value="{{$user->phone}}">
            </div>
            <div class="form-group mt-4 col-6">
                <label for="inputCCCD">Căn cước công dân @if ($errors->has('cccd'))<p class="text-error">*{{$errors->first('cccd')}}</p>@endif</label>
                <input type="text" name="cccd" class="form-control" id="inputCCCD" aria-describedby="nameHelp" value="{{$user->cccd}}">
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="inputGender">Giới tính @if ($errors->has('gender'))<p class="text-error">*{{$errors->first('gender')}}</p>@endif</label>
                <input type="text" name="gender" class="form-control" id="inputGender" aria-describedby="nameHelp" value="{{$user->gender}}">
            </div>
            <div class="form-group mt-4 col-6">
                <label for="inputAddress">Địa chỉ @if ($errors->has('address'))<p class="text-error">*{{$errors->first('address')}}</p>@endif</label>
                <input type="text" name="address" class="form-control" id="inputAddress" aria-describedby="nameHelp" value="{{$user->address}}">
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="inputDateOfBirth">Ngày sinh @if ($errors->has('date_of_birth'))<p class="text-error">*{{$errors->first('date_of_birth')}}</p>@endif</label>
                <input type="date" name="date_of_birth" class="form-control" id="inputDateOfBirth" aria-describedby="nameHelp" value="{{$user->date_of_birth}}">
            </div>
            <div class="form-group mt-4 col-6">
                <label for="inputCreatedAt">Ngày đăng ký tài khoản </label>
                <input type="text" class="form-control" id="inputCreatedAt" aria-describedby="nameHelp" readonly>
            </div>
        </div>
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="inputName">Ảnh đại diện @if ($errors->has('avatar'))<p class="text-error">*{{$errors->first('name')}}</p>@endif</label>
                <input type="file" name="name" class="form-control" id="inputName" aria-describedby="nameHelp" value="{{$user->name}}">
            </div>
            <div class="form-group mt-4 col-6">
                <label for="optionStatus">Trạng thái @if ($errors->has('status'))<p class="text-error">*{{$errors->first('status')}}</p>@endif</label>
                <select name="status" id="optionStatus" style="width: 50%; height: 50px;" class="select form-control mb-3" aria-label=".form-select-lg example">
                    <option value="1" {{$user->status == 1 ? 'selected' : ''}}>Hiển thị</option>
                    <option value="0" {{$user->status == 0 ? 'selected' : ''}}>Ẩn</option>
                </select>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
<script>
    $(document).ready(function () {
        let errCode = '{{$userErrCode}}';
        let errMsg = '{{$userErrMsg}}';

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
                    var url = '{{ route("dashboard.category.list") }}';
                    location.replace(url);
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
</script>
@endsection
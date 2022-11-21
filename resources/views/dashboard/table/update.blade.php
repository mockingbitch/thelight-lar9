@extends('layouts.dashboardLayout')
@section('content')
<h2>Chi tiết bàn</h2>
<div class="card mb-4">
    <form class="mx-4 pt-4" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group mt-4">
            <label for="inputName">Tên bàn @if ($errors->has('name'))<p class="text-error">*{{$errors->first('name')}}</p>@endif</label>
            <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp" value="{{$table->name}}">
        </div> 
        <div class="form-group mt-4">
            <label for="inputDescription">Mô tả @if ($errors->has('description'))<p class="text-error">*{{$errors->first('description')}}</p>@endif</label>
            <input type="text" name="description" class="form-control" id="inputDescription" aria-describedby="nameHelp" value="{{$table->description}}">
        </div>
        <div class="form-group mt-4 row">
            <div class="col-4">
                <label for="optionStatus">Trạng thái @if ($errors->has('status'))<p class="text-error">*{{$errors->first('status')}}</p>@endif</label>
                <select name="status" class="select form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                    <option value="1" {{$table->status == 1 ? 'selected' : ''}}>Hiển thị</option>
                    <option value="0" {{$table->status == 0 ? 'selected' : ''}}>Ẩn</option>
                </select>
            </div>
        </div>

        <a href="{{route('dashboard.table.list')}}"><input type="text" class="btn btn-secondary" value="Trở về" disabled></a>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>

<script>
    $(document).ready(function () {
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
                title: "Thất bại",
                text: errMsg,
                icon: "warning",
                button: "Đóng!",
                });
        }
    });
</script>
@endsection
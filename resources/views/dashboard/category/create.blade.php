@extends('layouts.dashboardLayout')
@section('content')
<h2>Thêm mới danh mục</h2>
<div class="card mb-4">
    <form class="mx-4 pt-4" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group mt-4">
            <label for="inputName">Tên danh mục @if ($errors->has('name'))<p class="text-error">*{{$errors->first('name')}}</p>@endif</label>
            <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp">
        </div> 
        <div class="form-group mt-4">
            <label for="inputDescription">Mô tả @if ($errors->has('description'))<p class="text-error">*{{$errors->first('description')}}</p>@endif</label>
            <input type="text" name="description" class="form-control" id="inputDescription" aria-describedby="nameHelp">
        </div>
        <div class="form-group mt-4">
            <label for="optionStatus">Trạng thái @if ($errors->has('status'))<p class="text-error">*{{$errors->first('status')}}</p>@endif</label>
            <select name="status" style="width: 20%; height: 50px; margin-left: 17px" class="select form-select-lg mb-3" aria-label=".form-select-lg example">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <a href="{{route('dashboard.category.list')}}"><input type="text" class="btn btn-secondary" value="Trở về" disabled></a>
        <button type="submit" class="btn btn-primary">Thêm mới</button>
    </form>
</div>
@endsection
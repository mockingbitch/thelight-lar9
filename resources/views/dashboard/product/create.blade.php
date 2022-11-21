@extends('layouts.dashboardLayout')
@section('content')
<h2>Thêm mới sản phẩm</h2>
<div class="card mb-4">
    <form class="mx-4 pt-4" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group mt-4">
            <label for="inputName">Tên sản phẩm @if ($errors->has('name'))<p class="text-error">*{{$errors->first('name')}}</p>@endif</label>
            <input type="text" name="name" class="form-control" id="inputName" aria-describedby="nameHelp" value="{{Request::old('name')}}">
        </div> 
        <div class="form-group mt-4">
            <label for="inputDescription">Mô tả @if ($errors->has('description'))<p class="text-error">*{{$errors->first('description')}}</p>@endif</label>
            <input type="text" name="description" class="form-control" id="inputDescription" aria-describedby="nameHelp" value={{Request::old('description')}}>
        </div>
        <div class="form-group mt-4">
            <label for="inputPrice">Giá @if ($errors->has('price'))<p class="text-error">*{{$errors->first('price')}}</p>@endif</label>
            <input type="text" name="price" class="form-control" id="inputPrice" aria-describedby="nameHelp" value="{{Request::old('price')}}">
        </div>
        <div class="row">
            <div class="form-group mt-4 col-6">
                <label for="optionCategory">Danh mục @if ($errors->has('category_id'))<p class="text-error">*{{$errors->first('category_id')}}</p>@endif</label>
                <select name="category_id" class="select form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}" {{Request::old('category_id') == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                    @endforeach 
                </select>
            </div>
            <div class="form-group mt-4 col-6">
                <label for="inputImage">Hình ảnh @if ($errors->has('image'))<p class="text-error">*{{$errors->first('image')}}</p>@endif</label>
                <input type="file" name="image" class="form-control" id="inputImage" aria-describedby="nameHelp" value="{{Request::old('image')}}">
            </div>
        </div>
        <div class="form-group mt-4 row">
            <div class="col-4">
                <label for="optionStatus">Trạng thái @if ($errors->has('status'))<p class="text-error">*{{$errors->first('status')}}</p>@endif</label>
                <select name="status" class="select form-control form-select-lg mb-3" aria-label=".form-select-lg example">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
        </div>

        <a href="{{route('dashboard.product.list')}}"><input type="text" class="btn btn-secondary" value="Trở về" disabled></a>
        <button type="submit" class="btn btn-primary">Thêm mới</button>
    </form>
</div>

<script>
    $(document).ready(function () {
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
                title: "Thất bại",
                text: errMsg,
                icon: "warning",
                button: "Đóng!",
                });
        }
    });
</script>
@endsection
@php
use App\Constants\ProductConstant;
use App\Constants\RouteConstant;
@endphp

@extends('layouts.homeLayout')
@section('content')
<div id="breadcrumb" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-xs-9 col-md-9">
                <ul class="breadcrumb-tree">
                    <li><a href="{{route(RouteConstant::HOMEPAGE)}}">Trang chủ</a></li>
                    <li><a href="{{route(RouteConstant::HOME['table_list'])}}">Bàn</a></li>
                    <li><a href="{{route(RouteConstant::HOME['table_detail'], ['id' => $table->id])}}">Chi tiết</a></li>
                    {{-- <li class="active">Order sản phẩm (20 Sản phẩm)</li> --}}
                </ul>
            </div>
            <div class="col-xs-3 col-md-3">
                <a href={{route(RouteConstant::HOME['table_detail'], ['id' => $table->id])}} class="btn btn-primary">Trở về</a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /BREADCRUMB -->
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- STORE -->
            <h2 style="margin-left: 20px">{{$table->name}}</h2>
            <div id="store" class="col-md-9">
                <!-- store top filter -->
                <div class="store-filter clearfix">
                    <div class="store-sort">
                        <label>
                            Sort By:
                            <select class="input-select">
                                <option value="0">Popular</option>
                                <option value="1">Position</option>
                            </select>
                        </label>

                        {{-- <label>
                            Show:
                            <select class="input-select">
                                <option value="0">20</option>
                                <option value="1">50</option>
                            </select>
                        </label> --}}
                    </div>
                    <ul class="store-grid">
                        <li class="active"><i class="fa fa-th"></i></li>
                        <li><a href="#"><i class="fa fa-th-list"></i></a></li>
                    </ul>
                </div>
                <!-- /store top filter -->

                <!-- store products -->
                <div class="row">
                    @foreach ($products as $product)
                        <!-- product -->
                        <div class="col-md-4 col-xs-6" onclick="handleAddOrder({{$product->id}}, {{$product->status}})">
                            <div class="product">
                                <div class="product-img">
                                    <img src="{{asset('upload/images/products/' . $product->image)}}" alt="">
                                    {!!
                                        $product->status == ProductConstant::STATUS['out_of_stock'] ?
                                        '<div class="product-label">
                                            <span class="new">Hết hàng</span>
                                        </div>'
                                        : ''
                                    !!}
                                </div>
                                <div class="product-body">
                                    <p class="product-category">{{$product->category->name}}</p>
                                    <h3 class="product-name"><a href="#">{{$product->name}}</a></h3>
                                    {{-- <h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4> --}}
                                    <h4 class="product-price">{{number_format($product->price)}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- /product -->
                    @endforeach
                </div>
                <!-- /store products -->

                <!-- store bottom filter -->
                {{-- <div class="store-filter clearfix">
                    <span class="store-qty">Showing 20-100 products</span>
                    <ul class="store-pagination">
                        <li class="active">1</li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                    </ul>
                </div> --}}
                <!-- /store bottom filter -->
            </div>
            <!-- /STORE -->
        </div>
        <!-- /row -->
    </div>

    <!-- /container -->
</div>
@include('scripts.script')
<script>
    $(document).ready(function () {
        let errCode = '{{$orderErrCode}}';
        let errMsg = '{{$orderErrMsg}}';

        if (errCode && errCode === '1' && errMsg) {
            Swal.fire(
                'Thất bại!',
                errMsg,
                'warning'
                )
        }
    });
</script>
@endsection
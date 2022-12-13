@php
use App\Constants\TableConstant;
@endphp

@extends('layouts.homeLayout')
@section('content')
@include('breadcrumbs.homeBreadcrumb')
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Bàn</h3>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab2" class="tab-pane fade in active">
                            <div class="tables row" data-nav="#slick-nav-2">
                                @foreach ($tables as $table)
                                    <!-- product -->
                                    <div class="product col-xs-6" onclick="handleClickTable({{$table->id}})">
                                        <div class="product-img">
                                            <img src="{{asset('upload/images/table.jpg')}}" alt="">
                                            <div class="product-label">
                                                @if ($table->status == TableConstant::STATUS_PENDING)
                                                    <span class="table-status-pending">
                                                        Đang chờ
                                                    </span>
                                                @elseif ($table->status == TableConstant::STATUS_ON_DELIVERY)
                                                    <span class="table-status-on-delivery">
                                                        Đang lên đồ
                                                    </span>
                                                @elseif ($table->status == TableConstant::STATUS_DELIVERED)
                                                    <span class="table-status-delivered">
                                                        Đã lên đồ
                                                    </span>
                                                @else
                                                    <span class="table-status-empty">
                                                        Bàn trống
                                                    </span>
                                                @endif
                                                {{-- <span class="done-checkout">$</span> --}}
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <h3 class="product-name"><a href="#">{{$table->name}}</a></h3>
                                            <div class="product-btns">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /product -->
                                @endforeach
                            </div>
                            <div id="slick-nav-2" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- /Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<script>
    function handleClickTable(id) {
        var url = '{{ route("home.table.detail", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }
</script>
@endsection
@extends('layouts.homeLayout')
@section('content')
<div class="shopping-cart section">
    <h1>{{$table->name}}</h1>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-12" id="list-cart">
                <!-- Shopping Summery -->
                <table class="table shopping-summery cart-change">
                    <thead>
                    <tr class="main-hading">
                        <th></th>
                        <th></th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Tổng</th>
                        <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><button class="delete" onclick=""><i class="fa fa-close"></i></button></td>
                        <td class="image" data-title="No"><img width="100px" src="{{asset('uploads/images/table.jpg')}}" alt="#"></td>
                        <td class="product-des" data-title="Description">
                            <p class="product-name"><a href="#">abc</a></p>

                        </td>
                        <td align="center" class="price" data-title="Price"><span>{{number_format(10000)}} Đ </span></td>
                        <td class="qty" data-title="Qty" align="center"><!-- Input Order -->
                            {{-- <div class="input-group sm">
                                <input onchange="" id="" type="number" name="quant[1]" class="input-number sm" min="1" value="">
                            </div> --}}
                            a
                            <!--/ End Input Order -->
                        </td>
                        <td align="center" class="total-amount" data-title="Total"><span>
                                
                        <td align="center" class="action" data-title="Remove"><a href="#" onclick=""><i class="ti-trash remove-icon"></i></a></td>
                    </tr>
                    </tbody>
                </table>
            <!--/ End Shopping Summery -->
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <!-- Total Amount -->
                <div class="total-amount">
                    <div class="row">
                        <div class="col-lg-4 col-md-7 col-12 col-xs-12" id="sub" >
                            <div class="right sub-change">
                                <div class="total" align="right" style="margin: 20px">
                                    Tổng: &emsp; &emsp; <span style="color: red;font-size: 30px;font-weight: bold">10000 Đ</span>
                                </div>
                                <hr />
                                <div class="button5" style="margin: 20px">
                                    <div class="row" align="right">
                                        <button style="margin-right: 20px" class="btn btn-default col-xs-5" onclick="handleOrder({{$table->id}})"><h4>Order</h4></button>
                                        <button class="btn btn-danger col-md-5" ><h4>Thanh toán</h4></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Total Amount -->
            </div>
        </div>
    </div>
</div>
<script>
    function handleOrder(id) {
        var url = '{{ route("home.order") }}?table=' + id;
        location.replace(url);
    }
</script>
@endsection
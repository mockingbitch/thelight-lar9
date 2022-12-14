@php
use App\Constants\RouteConstant;

	$order = session()->get('order');
	$table_id = null !== $order ? array_key_first($order) : null;
	$subTotal = 0;
@endphp
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>The Light</title>

		<!-- Google font -->
		<link href="{{asset('home/css/font-google.css?family=Montserrat:400,500,700')}}" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{asset('home/css/bootstrap.min.css')}}"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="{{asset('home/css/slick.css')}}"/>
		<link type="text/css" rel="stylesheet" href="{{asset('home/css/slick-theme.css')}}"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="{{asset('home/css/nouislider.min.css')}}"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="{{asset('home/css/font-awesome.min.css')}}">
		<link rel="stylesheet" href="{{asset('home/css/font-awesome6.css')}}">
		<script src="https://kit.fontawesome.com/915a42d302.js" crossorigin="anonymous"></script>


		{{-- Custom Style --}}
		<link rel="stylesheet" href="{{asset('home/css/thelight.css')}}">
		{{-- Custom Style --}}

		<script src="{{asset('dashboard/assets/js/swal.js')}}"></script>
		<script src="{{asset('home/js/swal2.js')}}"></script>
		<script src="{{asset('home/js/jquery.min.js')}}"></script>



		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="{{asset('home/css/style.css')}}"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>
		<button onclick="topFunction()" id="backTop" title="Go to top"><i class="fa fa-arrow-circle-up"></i></button>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			{{-- <div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
						<li><a href="#"><i class="fa fa-user-o"></i> My Account</a></li>
					</ul>
				</div>
			</div> --}}
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						{{-- <div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="./img/logo.png" alt="">
								</a>
							</div>
						</div> --}}
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						@if (isset($breadcrumb) && null !== $breadcrumb && $breadcrumb == 'products_order')
							<div class="col-xss-6">
								<div class="header-search">
									<form method="get">
										<select class="input-select" disabled>
											<option value="0">Tìm kiếm</option>
										</select>
										<input name="key" class="input search-product" placeholder="Nhập tên sản phẩm">
										<input type="hidden" name="table" value="{{$table->id ?? null}}">
										<button class="search-btn">Tìm kiếm</button>
									</form>
								</div>
							</div>
						@endif
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-xss-3 clearfix order-icon">
							<div class="header-ctn">
								<!-- Wishlist -->
								{{-- <div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty">2</div>
									</a>
								</div> --}}
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown orders {{null !== $order ? 'display-order' : ''}}">
									<a class="dropdown-toggle" data-toggle="dropdown">
										<i class="fa fa-shopping-cart"></i>
										<span>Order</span>
										<div class="qty">{{null !== $order && null !== $table_id ? count($order[$table_id]) : ''}}</div>
									</a>
									<div class="cart-dropdown">
										@if (null !== $order && null !== $order[$table_id])
										<div class="cart-list">
											@foreach ($order[$table_id] as $item)
											<div class="product-widget">
												<div class="product-img">
													<img src="{{asset('upload/images/products/' . $item['image'])}}" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">{{$item['name']}}</a></h3>
													<h4 class="product-price"><span class="qty">{{$item['quantity']}} X</span>{{number_format($item['price'])}}</h4>
												</div>
												<a class="delete" onclick="handleRemoveItemOrder({{$item['id']}})"><i class="fa fa-close"></i></a>
											</div>
											@php
												$subTotal += $item['quantity'] * $item['price']
											@endphp
											@endforeach
										</div>
										<div class="cart-summary">
											<small>{{count($order[$table_id])}} sản phẩm</small>
											<h5>Tổng: {{number_format($subTotal)}}</h5>
										</div>
										@endif
										<div class="cart-btns">
											<a onclick="handleRemoveOrder()">Xoá order</a>
											<a href="{{route(RouteConstant::HOME['order_submit'])}}">Xác nhận <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle" onclick="handleToggleMenu()">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->

		<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav">
						<li class="active"><a href="{{route(RouteConstant::HOMEPAGE)}}">Trang chủ</a></li>
						<li><a href="{{route(RouteConstant::DASHBOARD['home'])}}">Trang quản trị</a></li>
						{{-- <li><a href="#">Categories</a></li> --}}
						{{-- <li><a href="#">Laptops</a></li> --}}
						{{-- <li><a href="#">Smartphones</a></li> --}}
						{{-- <li><a href="#">Cameras</a></li> --}}
						{{-- <li><a href="#">Accessories</a></li> --}}
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->

		<!-- SECTION -->
		@yield('content')
		<!-- /SECTION -->
		@extends('scripts.script')
		<script>
			// Get the button
			let mybutton = document.getElementById("backTop");
			
			// When the user scrolls down 20px from the top of the document, show the button
			window.onscroll = function() {scrollFunction()};
			
			function scrollFunction() {
			  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				mybutton.style.display = "block";
			  } else {
				mybutton.style.display = "none";
			  }
			}
			
			// When the user clicks on the button, scroll to the top of the document
			function topFunction() {
			  document.body.scrollTop = 0;
			  document.documentElement.scrollTop = 0;
			}
			</script>
		<!-- jQuery Plugins -->
		<script src="{{asset('home/js/jquery.min.js')}}"></script>
		<script src="{{asset('home/js/bootstrap.min.js')}}"></script>
		<script src="{{asset('home/js/slick.min.js')}}"></script>
		<script src="{{asset('home/js/nouislider.min.js')}}"></script>
		<script src="{{asset('home/js/jquery.zoom.min.js')}}"></script>
		<script src="{{asset('home/js/main.js')}}"></script>
	</body>
</html>

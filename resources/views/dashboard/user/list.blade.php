@php
use App\Constants\RouteConstant;
use App\Constants\UserConstant;
@endphp

@extends('layouts.dashboardLayout')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách bàn</h3>
                <a href="{{route(RouteConstant::DASHBOARD['table_create'])}}">
                    <button class="btn btn-primary" style="float: right;">Thêm mới</button>
                </a>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Tên</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2"><b>Email</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2"><b>Giới tính</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2"><b>Vai trò</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>SĐT</b></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                        <tr class="tb-row" onclick="handleClickRow({{$user->id}})">
                            <td>
                                <div class="px-2 py-1">
                                    <div class="flex-column">
                                        <h6 class="mb-0">{{$user->name}}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{$user->email}}
                                </p>
                            </td>
                            <td>
                                @if(null !== $user->gender)
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{$user->gender == 1 ? 'Nam' : 'Nữ'}}
                                    </p>
                                @endif
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    @switch($user->role)
                                        @case(UserConstant::ROLE_ADMIN)
                                            Admin
                                            @break
                                        @case(UserConstant::ROLE_MANAGER)
                                            Quản lý
                                            @break
                                        @case(UserConstant::ROLE_WAITER)
                                            Phục vụ
                                            @break
                                        @default
                                    @endswitch
                                </p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{$user->phone}}
                                </p>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- {!! $products->links("pagination::bootstrap-4") !!} --}}
</div>
<script>
    function handleClickRow(id) {
        var url = '{{ route("dashboard.table.update", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }

    $(document).ready(function() {


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
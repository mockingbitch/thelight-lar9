@php
use App\Constants\RouteConstant;
use App\Constants\OrderConstant;
@endphp

@extends('layouts.homeLayout')
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card mb-4">
            <div class="card-header pb-0 mt-3 mb-4">
                <h3 align="center" style="text-shadow: 1px 1px 2px grey;">Danh sách order</h3>
            </div>

            <div class="card-body px-0 pt-0 pb-2 mt-4">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Bàn</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Trạng thái</b></th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9"><b>Nhân viên</b></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                                <tr class="tb-row" onclick="handleOnClickRow({{$item['id']}})">
                                    <td>
                                        <div class="d-flex px-2 py-1 margin-tbl-item">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h4 class="mb-0">{{$item['name']}}</h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if (null !== $item['order'])
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2 text-xs font-weight-bold">{{$item['percent']}}%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-{{$item['percent'] == 100 ? 'success' : 'warning'}}" role="progressbar" aria-valuenow="{{$item['percent']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$item['percent']}}%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center margin-tbl-item">
                                                <span class="me-2 text-xs font-weight-bold">Bàn trống</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (null !== $item['order'])
                                            <div class="d-flex align-items-center justify-content-center margin-tbl-item">
                                                <span class="me-2 text-xs font-weight-bold">
                                                    {{$item['order']->waiter->name}}
                                                </span>
                                            </div>
                                        @endif
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
    function handleOnClickRow(id) {
        var url = '{{ route("home.table.detail", ":id") }}';
        url = url.replace(':id', id);
        location.replace(url);
    }

    $(document).ready(function() {
        setInterval(() => {
            $('.table').load('{{route(RouteConstant::HOME['order_list'])}} .table');
        }, 3000);
        setInterval(() => {
            window.location.reload();
        }, 50000);
    });
</script>
@endsection
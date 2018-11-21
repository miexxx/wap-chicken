@extends('admin::layouts.main')

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">优惠卷列表</h3>

                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::coupons.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>


                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>优惠卷面值</th>
                            <th>使用门槛</th>
                            <th>优惠卷时效</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{$coupon->money}}元</td>
                                <td>{{$coupon->base_money}}元</td>
                                <td>{{$coupon->time}}天</td>
                                <td>{{ $coupon->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin::coupons.edit', $coupon->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $coupon->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $coupons->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::coupons.index')])

    <script>
        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection
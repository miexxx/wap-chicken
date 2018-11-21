@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">充值金额列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::recharge.create') }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;新增
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>充值金额</th>
                            <th>赠送</th>
                            <th>操作</th>
                        </tr>
                        @foreach($recharges as $recharge)
                            <tr>
                                <td>{{$recharge->money}}</td>
                                <td>{{ $recharge->free}}</td>
                                <td>
                                    <a href="{{ route('admin::recharge.edit', $recharge->id) }}" >
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <a href="javascript:void(0);" data-id="{{ $recharge->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $recharges->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::recharge.index')])
@endsection

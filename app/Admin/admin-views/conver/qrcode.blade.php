@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">兑换卷列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::qrcodes.create',0) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成（100元）兑换卷
                        </a>

                        <a href="{{ route('admin::qrcodes.create',1) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成（500元）兑换卷(鸡)
                        </a>

                    </div>
                </div>


                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>二维码</th>
                            <th>序列号</th>
                            <th>密码</th>
                            <th>兑换卷金额</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($qrcodes as $qrcode)
                            <tr>
                                <td>{{ $qrcode->id }}</td>
                                <td><img src="{{ $qrcode->qrcode }}" width="80" height="80" alt="" class="img-bordered"></td>
                                <td>{{$qrcode->sn}}</td>
                                <td>{{ $qrcode->password }}</td>
                                <td>@if($qrcode->type == 0)<span class="badge bg-red">100元</span>@else<span class="badge bg-green">500元</span>@endif</td>
                                <td>{{$qrcode->created_at }}</td>

                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $qrcode->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $qrcodes->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::qrcodes.index')])
@endsection

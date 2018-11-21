@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">实物卷列表</h3>
                    <div class="btn-group pull-right">
                        <a href="{{ route('admin::convers.create',1) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成实物卷(母鸡)
                        </a>

                        <a href="{{ route('admin::convers.create',2) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成实物卷(黄壳蛋)
                        </a>

                        <a href="{{ route('admin::convers.create',3) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成实物卷(绿壳蛋)
                        </a>

                        <a href="{{ route('admin::convers.create',4) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;批量生成实物卷(公鸡)
                        </a>

                    </div>
                </div>


                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>序列号</th>
                            <th>密码</th>
                            <th>实物卷类型</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($convers as $conver)
                            <tr>
                                <td>{{ $conver->id }}</td>
                                <td>{{$conver->sn}}</td>
                                <td>{{ $conver->password }}</td>
                                <td>@if($conver->type == 1)<span class="badge bg-red">母鸡</span>@elseif($conver->type == 2)<span class="badge bg-green">黄壳蛋</span>
                                    @elseif($conver->type == 3)<span class="badge bg-green">绿壳蛋</span>
                                    @elseif($conver->type == 4)<span class="badge bg-green">公鸡</span>
                                    @endif</td>
                                <td>{{$conver->created_at }}</td>

                                <td>
                                    <a href="javascript:void(0);" data-id="{{ $conver->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $convers->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::convers.index')])
@endsection

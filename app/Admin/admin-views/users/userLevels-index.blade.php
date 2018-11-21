@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">等级列表</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>等级</th>
                            <th>等级名称</th>
                            <th>推广消费金额(升级条件)</th>
                            <th>销售佣金(百分比)</th>
                            <th>邀请佣金(百分比)</th>
                            <th>操作</th>
                        </tr>
                        @foreach($userLevels as $userLevel)
                            <tr>
                                <td>{{ $userLevel->level }}</td>
                                <td>{{ $userLevel->name }}</td>
                                <td>&yen;{{ $userLevel->money }}</td>
                                <td>{{ $userLevel->sales_percent }}%</td>
                                <td>{{ $userLevel->invite_percent }}%</td>
                                <td>
                                    <a href="{{ route('admin::userLevels.edit', $userLevel->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $userLevels->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
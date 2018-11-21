@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">消息通知列表</h3>

                    {{--<div class="btn-group pull-right">--}}
                        {{--<a href="{{ route('admin::banners.create') }}" class="btn btn-sm btn-success">--}}
                            {{--<i class="fa fa-save"></i>&nbsp;&nbsp;新增--}}
                        {{--</a>--}}
                    {{--</div>--}}
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>模板ID</th>
                            <th>模板标题</th>
                            <th>模板内容</th>
                        </tr>
                        @foreach($templates as $template)
                            <tr>
                                <td>{{ $template['template_id'] }}</td>
                                <td>{{ $template['title'] }}</td>
                                <td>{{ $template['content'] }}</td>
                                {{--<td>--}}
                                    {{--<a href="{{ route('admin::banners.edit', $banner->id) }}">--}}
                                        {{--<i class="fa fa-edit"></i>--}}
                                    {{--</a>--}}
                                    {{--<a href="javascript:void(0);" data-id="{{ $banner->id }}" class="grid-row-delete">--}}
                                        {{--<i class="fa fa-trash"></i>--}}
                                    {{--</a>--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    {{--@include('admin::js.grid-row-delete', ['url' => route('admin::banners.index')])--}}
@endsection
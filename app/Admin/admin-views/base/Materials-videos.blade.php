@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">视频列表</h3>
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0);" onclick="uploadVideo()" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;获取最新图文素材
                        </a>
                    </div>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>视频ID</th>
                            <th>视频名称</th>
                            <th>视频介绍</th>
                            <th>视频url</th>
                            <th>更新时间</th>
                        </tr>
                        @foreach($list as $item)
                            <tr>
                                <td>{{ $item['media_id'] }}</td>
                                <td>
                                    {{ $item['name'] }}
                                </td>
                                <td>
                                    {{ $item['description'] }}
                                </td>
                                <td>
                                    <a href="{{ $item['url'] }}" target="_blank">点击观看</a>
                                </td>
                                <td>{{ date("Y-m-d H:i",$item['update_time']) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        {!! $list->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function uploadVideo(){
            swal({
                title: "更新中，请稍等",
                type: "info",
                showConfirmButton: false,
                closeOnConfirm: false
            });
            $.ajax({
                method: 'get',
                url: "{{ route('admin::materials.uploadVideos') }}",
                data: {
                    _token: LA.token
                },
                success: function (data) {
                    if (typeof data === 'object') {
                        if(data.status) {
                            $.pjax.reload('#pjax-container');
                            swal(data.message, '', 'success');
                        }
                        else {
                            swal(data.message, '', 'error');
                        }
                    }
                }
            });
        }
    </script>
@endsection
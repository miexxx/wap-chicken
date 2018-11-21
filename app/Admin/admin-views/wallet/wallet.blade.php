@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">提现申请列表</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>申请人昵称</th>
                            <th>联系方式</th>
                            <th>提现金额</th>
                            <th>申请时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($applys as $apply)
                            <tr>
                                <td>{{ $apply->id }}</td>
                                <td>{{$apply->user->nickname}}</td>
                                <td>{{ $apply->user->phone }}</td>
                                <td>{{$apply->money}}</td>
                                <td>{{ $apply->created_at }}</td>
                                <td>@if($apply->state == 0)<span class="badge bg-red">审核中</span>@else<span class="badge bg-green">已通过</span>@endif</td>
                                <td>
                                    <a >
                                        <i class="fa fa-apple apply" data-id="{{ $apply->id }}" ></i>
                                    </a>

                                    <a href="javascript:void(0);" data-id="{{ $apply->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $applys->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::withdraw.index')])
    <script>
        $('.apply').unbind('click').click(function() {

            var id = $(this).data('id');
            swal({
                    title: "确认操作?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    cancelButtonText: "取消"
                },
                function(){
                    $.ajax({
                        method: 'get',
                        url: '/admin/withdraw/success' + '/' + id,
                        data: {
                            _token:LA.token
                        },
                        success: function (data) {
                            $.pjax.reload('#pjax-container');

                            if (typeof data === 'object') {
                                if (data.status) {
                                    swal(data.message, '', 'success');
                                } else {
                                    swal(data.message, '', 'error');
                                }
                            }
                        }
                    });
                });
        });
    </script>
@endsection

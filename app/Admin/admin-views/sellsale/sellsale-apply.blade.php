@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">委托代卖申请列表</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>申请人昵称</th>
                            <th>联系方式</th>
                            <th>代卖商品</th>
                            <th>申请时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                        @foreach($sellsales as $sellsale)
                            <tr>
                                <td>{{ $sellsale->id }}</td>
                                <td>{{$sellsale->user->nickname}}</td>
                                <td>{{ $sellsale->user->phone }}</td>
                                <td>
                                    <a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $sellsale->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $sellsale->id }}" aria-expanded="false">
                                        <i class="fa fa-caret-right"></i> 详情
                                    </a>
                                    <template class="grid-expand-{{ $sellsale->id }}">
                                        <div id="grid-collapse-{{ $sellsale->id }}" class="collapse">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">商品详情</h3>
                                                    <div class="box-tools pull-right">
                                                    </div>
                                                </div>
                                                <div class="box-body" style="display: block;">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>商品图片</th>
                                                            <th>商品名称</th>
                                                            <th>商品价格</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>

                                                                <td>{!! $itemPresenter->cover($sellsale->support->item) !!}</td>
                                                                <td>{{ $sellsale->support->item->title }}</td>
                                                                <td>{{ $sellsale->support->item->price }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>{{ $sellsale->created_at }}</td>
                                <td>@if($sellsale->status == 0)<span class="badge bg-red">审核中</span>@else<span class="badge bg-green">已通过</span>@endif</td>
                                <td>
                                    <a >
                                        <i class="fa fa-apple apply" data-id="{{ $sellsale->id }}" ></i>
                                    </a>

                                    <a href="javascript:void(0);" data-id="{{ $sellsale->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $sellsales->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::sellsale.apply')])
    <script>

        $('.grid-expand').on('click', function () {
            if ($(this).data('inserted') == '0') {
                var key = $(this).data('key');
                var row = $(this).closest('tr');
                var html = $('template.grid-expand-'+key).html();

                row.after("<tr><td colspan='"+row.find('td').length+"' style='padding:0 !important; border:0px;'>"+html+"</td></tr>");

                $(this).data('inserted', 1);
            }

            $("i", this).toggleClass("fa-caret-right fa-caret-down");
        });



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
                        url: '/admin/sellsale/success' + '/' + id,
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

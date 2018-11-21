@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">文章列表</h3>
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0);" onclick="uploadArticle()" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;获取最新图文素材
                        </a>
                    </div>

                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>微信media_id</th>
                            <th>文章封面</th>
                            <th>文章标题</th>
                            <th>文章详情</th>
                            <th>所属栏目</th>
                            <th>微信更新时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($list as $article)
                            <tr>
                                <td>{{ $article->id }}</td>
                                <td>{{ $article->article->media_id }}</td>
                                <td><a href="javascript:void(0);" onclick="showImage(this)"  data-image="{{ $article->thumb_url }}" data-toggle="modal" data-target="#imageModal" >预览</a></td>
                                <td>{{ $article->title }}</td>
                                <td><a href="{{ $article->url }}" target="_blank" >查看</a></td>
                                <td><span class="label label-info">{{ $article->scope_text }}</span></td>
                                <td>{{ date("Y-m-d H:i", $article->article->update_time) }}</td>
                                <td>  <a href=""
                                         data-toggle="modal"
                                         data-action="{{ route('admin::materials.setScope', $article->id) }}"
                                         data-target="#scope-modal"
                                         style="padding:3px 6px;"
                                         title="编辑类别" class="btn btn-info btn-sm grid-row-edit" data-scope="{{ $article->scope }}" onclick="setScope(this)" role="button">
                                        <i class="fa fa-edit"></i> 设置文章类别
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="modal fade text-center" id="imageModal">
                        <div class="modal-dialog modal-lg" style="display: inline-block;width: auto;" >
                            <div class="modal-content">

                                <!-- 模态框头部 -->
                                <div class="modal-header">
                                    <h4 class="modal-title text-left">封面图</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- 模态框主体 -->
                                <div class="modal-body" id="displayImage">

                                </div>

                                <!-- 模态框底部 -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">关闭</button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal fade" id="scope-modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">设置类别</h4>
                                </div>
                                <form id="post-scope-form" action="" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="modal-body">
                                        <div class="form">
                                            <div class="form-group">
                                                <label>选择类别</label>
                                                <select class="form-control scope" style="width: 100%;" id="scope" name="scope" data-placeholder="选择类别"  >
                                                    <option value="1" >鸡笼百科</option>
                                                    <option value="2" >公司活动</option>
                                                    <option value="3" >最新公告</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" id="reset-create" class="btn btn-warning pull-left">清空</button>
                                        <button type="button" class="btn btn-primary" data-id="" id="deliver-btn" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        {{--{!! $list->render() !!}--}}
                        {{ $list->links('admin::widgets.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>

        function showImage(obj) {
            var path = $(obj).data('image');
            $('#displayImage').html("<image src='"+ path + "' class='carousel-inner img-responsive img-rounded' />");
        }

        function uploadArticle(){
            swal({
                title: "更新中，请稍等",
                type: "info",
                showConfirmButton: false,
                closeOnConfirm: false
            });
            $.ajax({
                method: 'get',
                url: '{{ route('admin::materials.updateList') }}',
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

        function setScope(obj) {
            var action = $(obj).data('action');
            $("#scope > option").each(function () {
                //console.log($(obj).data('grade'));
                if($(this).val() == $(obj).data('scope')) {
                    $(this).prop('selected',true);
                    return;
                }
            });
            $('#post-scope-form').attr('action', action);
        }

        $('#deliver-btn').click(function () {
            var scope_form = $('#post-scope-form');
            $.ajax({
                url: scope_form.attr('action'),
                type: 'PUT',
                data: scope_form.serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        $.pjax.reload('#pjax-container');
                        swal({
                            title:res.message,
                            type:'success',
                            timer:1500
                        });
                    }
                    $("#scope-modal").modal('toggle');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            });
        });

    </script>
@endsection

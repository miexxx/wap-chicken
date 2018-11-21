@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑联系方式</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-refresh"></i>&nbsp;刷新</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::contacts.update',$contact->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="address" class="col-sm-2 control-label">地址</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="address" name="address" value="{{ $contact->address }}" class="form-control" placeholder="输入 地址">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label">联系电话</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="phone" name="phone" value="{{ $contact->phone }}" class="form-control" placeholder="输入 联系电话">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">电子邮箱</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="email" name="email" value="{{ $contact->email }}" class="form-control" placeholder="输入 电子邮箱">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wechat_no" class="col-sm-2 control-label">微信号</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="wechat_no" name="wechat_no" value="{{ $contact->wechat_no }}" class="form-control" placeholder="输入 微信号">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="covers" class="col-sm-2 control-label">二维码</label>
                                <div class="col-sm-8">
                                    <input type="file" class="code_img" name="code_img" id="code_img" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <span id="prompt-info" style="color:#f00;"></span>
                            <button type="button" id="submit-btn"  class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                location.reload();
            });

            $('#submit-btn').on('click', function (event) {
                $('#post-form').submit();
                swal("提交成功！","请继续操作！","success");
            });

            ///
            var urls = [];
            var j = {};
            var url = "{{$contact->code_img}}";
            if(url) {
                j.downloadUrl = "{{ $contact->code_img }}";
                urls.push(j.downloadUrl);
            }



            $(".code_img").fileinput({
                overwriteInitial: false,
                initialPreviewAsData: true,
                initialPreview: urls,
                browseLabel: "浏览",
                showRemove: false,
                showUpload: false,
                allowedFileTypes: [
                    "image"
                ]
            });
        });
    </script>
@endsection
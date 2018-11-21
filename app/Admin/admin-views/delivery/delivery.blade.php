@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">分期配送管理</h3>
                    <div class="box-tools">
                         <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::deliverys.store') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="about_us" class="col-sm-2 control-label">分期配送数量设置</label>
                                <div class="col-sm-8">

                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#week" role="tab" data-toggle="tab">按周</a></li>
                                        <li role="presentation"><a href="#month" role="tab" data-toggle="tab">按月</a></li>
                                    </ul>

                                    <!-- 面板区 -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="week">
                                            @foreach($wdeliverys as $key=>$wdelivery)
                                            <div class="form-group  weeknode" style="margin-top: 10px;">
                                                <label for="name" class="col-sm-2 control-label">数量(周)</label>
                                                <div class="col-sm-4" >
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <input type="text" id="wnumber" name="wnumber[]" value="{{$wdelivery->number}}" class="form-control name" placeholder="输入 数量">
                                                    </div>
                                                </div>
                                                @if($key ==0)
                                                <div class="col-sm-4" >
                                                <a href="#" class="btn btn-primary btn-sm add_week" role="button">添加数量</a>
                                                <a href="#" class="btn btn-primary btn-sm delete_week" role="button">删除数量</a>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="month">
                                            @foreach($mdeliverys as $key=>$mdelivery)
                                            <div class="form-group monthnode" style="margin-top: 10px;">
                                                <label for="name" class="col-sm-2 control-label">数量(月)</label>
                                                <div class="col-sm-4">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                                        <input type="text" id="mnumber" name="mnumber[]" value="{{$mdelivery->number}}" class="form-control name" placeholder="输入 数量">
                                                    </div>


                                                </div>
                                                @if($key ==0)
                                                <div class="col-sm-4" >
                                                <a href="#" class="btn btn-primary btn-sm add_month" role="button">添加数量</a>
                                                <a href="#" class="btn btn-primary btn-sm delete_month" role="button">删除数量</a>
                                                </div>
                                                    @endif

                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="form-group " style="margin-top: 10px;">
                            <label for="name" class="col-sm-2 control-label">连续配送周数</label>
                            <div class="col-sm-2" >
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil">（周）</i></span>
                                    @foreach( $wtimes as $wtime)
                                        <input type="text"  name="wtime[]" value="{{$wtime->number}}" class="form-control " placeholder="连续配送周数">
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="form-group " style="margin-top: 10px;">
                            <label for="name" class="col-sm-2 control-label">连续配送月数</label>
                            <div class="col-sm-2" >
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil">（月）</i></span>
                                    @foreach( $mtimes as $mtime)
                                        <input type="text"  name="mtime[]" value="{{$mtime->number}}" class="form-control " placeholder="连续配送周数">
                                    @endforeach
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

            $('.add_week').click(function(){
                if( $('.weeknode').length<4) {
                    $('#week').append("<div class=\"form-group weeknode\" style=\"margin-top: 10px;\">\n" +
                        "                                                <label for=\"name\" class=\"col-sm-2 control-label\">数量(周)<\/label>\n" +
                        "                                                <div class=\"col-sm-4\" >\n" +
                        "                                                    <div class=\"input-group\">\n" +
                        "                                                        <span class=\"input-group-addon\"><i class=\"fa fa-pencil\"><\/i><\/span>\n" +
                        "                                                        <input type=\"text\" id=\"wnumber\" name=\"wnumber[]\" value=\"\" class=\"form-control name\" placeholder=\"输入 数量\">\n" +
                        "                                                    <\/div>\n" +
                        "                                                <\/div>\n" +
                        "                                            \n" +
                        "\n" +
                        "                                            <\/div>");
                }
            });

            $('.delete_week').click(function(){
                if( $('.weeknode').length>1) {
                    $('.weeknode').last().remove();
                }
            });

            $('.add_month').click(function(){
                if( $('.monthnode').length<4) {
                    $('#month').append("<div class=\"form-group monthnode\" style=\"margin-top: 10px;\">\n" +
                        "                                                <label for=\"name\" class=\"col-sm-2 control-label\">数量(月)<\/label>\n" +
                        "                                                <div class=\"col-sm-4\" >\n" +
                        "                                                    <div class=\"input-group\">\n" +
                        "                                                        <span class=\"input-group-addon\"><i class=\"fa fa-pencil\"><\/i><\/span>\n" +
                        "                                                        <input type=\"text\" id=\"mnumber\" name=\"mnumber[]\" value=\"\" class=\"form-control name\" placeholder=\"输入 数量\">\n" +
                        "                                                    <\/div>\n" +
                        "                                                <\/div>\n" +
                        "                                            \n" +
                        "\n" +
                        "                                            <\/div>");
                }
            });

            $('.delete_month').click(function(){
                if( $('.monthnode').length>1) {
                    $('.monthnode').last().remove();
                }
            });

            var $rule = {
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    'wnumber[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入数量'
                            }
                        }
                    },
                    'mnumber[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入数量'
                            }
                        }
                    },
                    'wtime[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入连续周数'
                            }
                        }
                    },
                    'mtime[]':{
                        validators:{
                            notEmpty:{
                                message: '请输入连续月数'
                            }
                        }
                    },
                }
            };

            $('#submit-btn').on('click', function (event) {
                var $form = $("#post-form");

                $form.bootstrapValidator($rule);
                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                    swal("提交成功！","请继续操作！","success");
                }
            });
        });
    </script>
@endsection
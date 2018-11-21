@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">编辑</h3>
                    <div class="box-tools">
                        <div class="btn-group pull-right" style="margin-right: 10px">
                            <a href="{{ route('admin::userLevels.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;列表</a>
                        </div> <div class="btn-group pull-right" style="margin-right: 10px">
                            <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                        </div>
                    </div>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::userLevels.update', $userLevel->id) }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">等级名称</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="name" name="name" value="{{ $userLevel->name }}" class="form-control name" placeholder="输入 等级名称">
                                    <input type="hidden" id="current_name" value="{{ $userLevel->name }}" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order" class="col-sm-2 control-label">等级</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                    <input type="text" id="level" name="level" value="{{ $userLevel->level }}" class="form-control level" placeholder="输入 等级">
                                    <input type="hidden" id="current_level" value="{{ $userLevel->level }}" >
                                </div>
                            </div>
                            <label class="col-sm-6 control-label" style="color:red;border:0;text-align:left;">会员等级，越大等级越高</label>
                        </div>
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="money" class="col-sm-2 control-label">推广消费金额(元)</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="money" name="money" value="{{ $userLevel->money }}" @if($userLevel->level == 1)readonly @endif class="form-control money"  placeholder="输入 金额">
                                    </div>
                                </div>
                                <label class="col-sm-6 control-label" style="color:red;border:0;text-align:left;">@if($userLevel->level == 1)该分享家为最低等级,不可更改@endif</label>
                            </div>
                        </div>
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="sales" class="col-sm-2 control-label">销售佣金(百分比)</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="sales" style="padding:12px;" name="sales_percent" value="{{ $userLevel->sales_percent }}" class="form-control sales" placeholder="输入 百分比">
                                    </div>
                                </div>
                                <label class="col-sm-6 control-label" style="color:red;border:0;text-align:left;"></label>
                            </div>
                        </div>
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="invite" class="col-sm-2 control-label">邀请佣金(百分比)</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="invite" style="padding:12px;" name="invite_percent" value="{{ $userLevel->invite_percent }}" class="form-control invite" placeholder="输入 百分比">
                                    </div>
                                </div>
                                <label class="col-sm-6 control-label" style="color:red;border:0;text-align:left;"></label>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <input type="submit" class="btn btn-info" id="submit-btn"  data-loading-text="提交" value="提交">
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
                history.back();
            });

            // $(".money").bootstrapNumber({
            //     'upClass': 'success',
            //     'downClass': 'primary',
            //     'center': true
            // });
            //
            // $(".sales").bootstrapNumber({
            //     'upClass': 'success',
            //     'downClass': 'primary',
            //     'center': true
            // });
            //
            // $(".invite").bootstrapNumber({
            //     'upClass': 'success',
            //     'downClass': 'primary',
            //     'center': true
            // });

            ///
            $("#post-form").bootstrapValidator({
                live: 'enable',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: '请输入等级名称'
                            },
                            stringLength: {
                                max: 32,
                                message: '等级名称长度不能超过32个字符'
                            },
                            remote: {
                                url: "{{ route('admin::userLevels.checkName') }}",
                                message: '等级名称已存在',
                                delay: 200,
                                type: 'get',
                                data: {
                                    current_name: function () {
                                        return $('#current_name').val()
                                    }
                                }
                            }
                        }
                    },
                    level: {
                        validators: {
                            notEmpty: {
                                message: '等级不能为空'
                            },
                            regexp: {
                                regexp: /^[1-9]\d*$/,
                                message: '等级必须为正整数',
                            },
                            remote: {
                                url: "{{ route('admin::userLevels.checkLevel') }}",
                                message: '该等级已存在',
                                delay: 200,
                                type: 'get',
                                data: {
                                    current_level: function () {
                                        return $('#current_level').val()
                                    }
                                }
                            }
                        }
                    },
                    money: {
                        validators: {
                            notEmpty: {
                                message: '消费金额不能为空'
                            },
                            regexp: {
                                regexp: /(^[1-9](\d+)?(\.\d{1,2})?$)|(^0$)|(^\d\.\d{1,2}$)/,
                                message: '金额只能为正数,且最多只能有两个小数'
                            }
                        }
                    },
                    sales_percent: {
                        validators: {
                            notEmpty: {
                                message: '销售佣金百分比不能为空'
                            },
                            digits : {
                                message : '必须是正整数'
                            },
                            greaterThan:{
                                value : 1,
                                message : '最小输入1',
                            },
                            lessThan: {
                                value : 100,
                                message : '最大输入100'
                            }
                        }
                    },
                    invite_percent: {
                        validators: {
                            notEmpty: {
                                message: '邀请佣金百分比不能为空'
                            },
                            digits : {
                                message : '必须是正整数'
                            },
                            greaterThan:{
                                value : 1,
                                message : '最小输入1',
                            },
                            lessThan: {
                                value : 100,
                                message : '最大输入100'
                            }
                        }
                    },
                }
            });

            $("#submit-btn").click(function () {
                var $form = $("#post-form");
                var name = $("#name").val();
                //var level = $("#level").val();

                if (name == "{{ $userLevel->name }}") {
                    $('#post-form').bootstrapValidator('enableFieldValidators', 'name', false);
                } else {
                    $('#post-form').bootstrapValidator('enableFieldValidators', 'name', true);
                }

                {{--if(level == "{{ $userLevel->level }}") {--}}
                {{--$('#post-form').bootstrapValidator('enableFieldValidators', 'level', false);--}}
                {{--} else {--}}
                {{--$('#post-form').bootstrapValidator('enableFieldValidators', 'level', true);--}}
                {{--}--}}

                $form.bootstrapValidator('validate');
                if ($form.data('bootstrapValidator').isValid()) {
                    $form.submit();
                }
            });
        });
    </script>
@endsection
@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">委托代卖</h3>
                </div>
                <form id="post-form" class="form-horizontal" action="{{ route('admin::sellsale.update') }}" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">卖鸡蛋利润</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="egg" name="egg" value="{{$egg}}" class="form-control name" placeholder="输入 卖鸡蛋利润">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">卖鸡抽取</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="chicken" name="chicken" value="{{$chicken}}" class="form-control name" placeholder="输入 卖鸡抽取">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button type="submit" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>


        $("#post-form").bootstrapValidator({
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                egg:{
                    validators:{
                        notEmpty:{
                            message: '卖鸡蛋利润不能为空'
                        }
                    }
                },
                chicken:{
                    validators:{
                        notEmpty:{
                            message: '卖鸡抽取利润不能为空'
                        }
                    }
                }
            }
        });

        $('#submit-btn').unbind('click').click(function() {
            $('#post-form').submit();
            swal("提交成功！","请继续操作！","success");
        });


    </script>
@endsection
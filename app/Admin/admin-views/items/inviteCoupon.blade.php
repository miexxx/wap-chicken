@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">设置分享朋友圈优惠卷</h3>
                </div>
                <form id="post-form" class="form-horizontal" action="" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">优惠卷面值(元)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="money" name="money" value="{{$coupon->money}}" class="form-control name" placeholder="输入 优惠卷金额">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="base_money" class="col-sm-3 control-label">使用门槛(元)</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="base_money" name="base_money" value="{{$coupon->base_money}}" class="form-control name" placeholder="输入门槛金额">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">优惠卷有效期</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"> 领券开始到第</span>
                                        <input type="text" id="time" name="time" value="{{$coupon->time}}" class="form-control name" placeholder="输入天数">
                                        <span class="input-group-addon">天有效</span>
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
                            <button type="button" id="submit-btn" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交">提交</button>
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
                history.back(1);
            });
        });

        $("#post-form").bootstrapValidator({
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                money:{
                    validators:{
                        notEmpty:{
                            message: '优惠卷金额不能为空'
                        },
                        numeric:{
                            message: '输入金额必须为整数'
                        }
                    }
                },
                time:{
                    validators:{
                        notEmpty:{
                            message: '优惠卷金额不能为空'
                        },
                        numeric:{
                            message: '输入时效必须为整数'
                        }
                    }
                }
            }
        });

        $("#submit-btn").click(function () {
            var $form = $("#post-form");

            $form.bootstrapValidator('validate');
            if ($form.data('bootstrapValidator').isValid()) {
                $.ajax({
                    method: 'post',
                    url: '{{ route('admin::coupons.update',$coupon->id) }}',
                    data: $form.serialize(),
                    success: function (data) {
                        if (typeof data === 'object') {
                            if (data.status) {
                                $.pjax.reload('#pjax-container');
                                swal(data.message, '', 'success');
                            }
                        }
                    }
                });
            }
        })
    </script>
@endsection
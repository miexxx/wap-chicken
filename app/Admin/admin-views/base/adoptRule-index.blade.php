@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">认养规则</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>认养年限</th>
                            <th>价格</th>
                            <th>年限可提鸡蛋数</th>
                            <th>操作</th>
                        </tr>
                        <tr>
                            <td>{{ $adoptRule->year }}(年)</td>
                            <td>&yen;{{ $adoptRule->price }}</td>
                            <td>{{ $adoptRule->egg_num }}(个)</td>
                            <td>
                                <a href=""
                                   data-action="{{ route('admin::adoptRules.update', $adoptRule->id) }}"
                                   data-toggle="modal"
                                   data-target="#edit-modal"
                                   style="padding:3px 6px;"
                                   title="编辑认养价格" class="btn btn-info btn-sm grid-row-edit" onclick="edit(this)" role="button">
                                    <i class="fa fa-edit"></i> 修改价格
                                </a>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="edit-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">编辑价格</h4>
                            </div>
                            <form id="post-edit-form" action="" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>价格</label>
                                            <input type="text" class="form-control" id="price" name="price" value="{{ $adoptRule->price }}" placeholder="价格" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="reset" id="reset-edit" class="btn btn-warning pull-left" value="清空" />
                                    <input type="submit" onclick="return false;" class="btn btn-primary" data-id="" id="deliver-btn"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交" value="提交">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var edit_condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                price: {
                    validators: {
                        notEmpty: {
                            message: '请输入价格'
                        },
                        numeric:  {
                            message: '价格必须为一个数字'
                        },
                    }
                },
            }
        };

        var edit_form = $("#post-edit-form");

        $('#edit-modal').on('hidden.bs.modal',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        $('#reset-edit').on('click',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        edit_form.bootstrapValidator(edit_condition);

        $('#deliver-btn').click(function () {
            var price = $('#price').val();

            edit_form.bootstrapValidator('validate');
            if (edit_form.data('bootstrapValidator').isValid()) {
                $.ajax({
                    url: edit_form.attr('action'),
                    type: 'PUT',
                    data: edit_form.serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            $.pjax.reload('#pjax-container');
                            swal(res.message, '', 'success');
                        }
                        else {
                            swal(res.message, '', 'error');
                            edit_form[0].reset();
                            $(".has-feedback").removeClass('has-success').removeClass('has-error');
                            $(".form-control-feedback").hide();
                        }
                        $("#edit-modal").modal('toggle');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            }
            else {
                return false;
            }
        });


        function edit(obj) {
            var action = $(obj).data('action');
            $('#post-edit-form').attr('action', action);
        }

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

    </script>
@endsection@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">认养规则</h3>
                </div>

                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>认养年限</th>
                            <th>价格</th>
                            <th>年限可提鸡蛋数</th>
                            <th>操作</th>
                        </tr>
                        <tr>
                            <td>{{ $adoptRule->year }}(年)</td>
                            <td>&yen;{{ $adoptRule->price }}</td>
                            <td>{{ $adoptRule->egg_num }}(个)</td>
                            <td>
                                <a href=""
                                   data-action="{{ route('admin::adoptRules.update', $adoptRule->id) }}"
                                   data-toggle="modal"
                                   data-target="#edit-modal"
                                   style="padding:3px 6px;"
                                   title="编辑认养价格" class="btn btn-info btn-sm grid-row-edit" onclick="edit(this)" role="button">
                                    <i class="fa fa-edit"></i> 修改价格
                                </a>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="edit-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">编辑价格</h4>
                            </div>
                            <form id="post-edit-form" action="" method="post">
                                {{ csrf_field() }}
                                <div class="modal-body">
                                    <div class="form">
                                        <div class="form-group">
                                            <label>价格</label>
                                            <input type="text" class="form-control" id="price" name="price" value="{{ $adoptRule->price }}" placeholder="价格" />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="reset" id="reset-edit" class="btn btn-warning pull-left" value="清空" />
                                    <input type="submit" onclick="return false;" class="btn btn-primary" data-id="" id="deliver-btn"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> 提交" value="提交">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        var edit_condition = {
            live: 'enable',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                price: {
                    validators: {
                        notEmpty: {
                            message: '请输入价格'
                        },
                        numeric:  {
                            message: '价格必须为一个数字'
                        },
                    }
                },
            }
        };

        var edit_form = $("#post-edit-form");

        $('#edit-modal').on('hidden.bs.modal',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        $('#reset-edit').on('click',function () {
            edit_form.bootstrapValidator('destroy');
            edit_form.data('bootstrapValidator',null);
            edit_form.bootstrapValidator(edit_condition);
        });

        edit_form.bootstrapValidator(edit_condition);

        $('#deliver-btn').click(function () {
            var price = $('#price').val();

            edit_form.bootstrapValidator('validate');
            if (edit_form.data('bootstrapValidator').isValid()) {
                $.ajax({
                    url: edit_form.attr('action'),
                    type: 'PUT',
                    data: edit_form.serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.status) {
                            $.pjax.reload('#pjax-container');
                            swal(res.message, '', 'success');
                        }
                        else {
                            swal(res.message, '', 'error');
                            edit_form[0].reset();
                            $(".has-feedback").removeClass('has-success').removeClass('has-error');
                            $(".form-control-feedback").hide();
                        }
                        $("#edit-modal").modal('toggle');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }
                });
            }
            else {
                return false;
            }
        });


        function edit(obj) {
            var action = $(obj).data('action');
            $('#post-edit-form').attr('action', action);
        }

        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

    </script>
@endsection
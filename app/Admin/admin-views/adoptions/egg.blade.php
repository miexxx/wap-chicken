@extends('admin::layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">认养年限可提取鸡蛋数</h3>
                </div>
                <form id="post-form" class="form-horizontal" action="#" method="post" enctype="multipart/form-data" pjax-container>
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">认养年限可提取鸡蛋数(个)</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                        <input type="text" id="money" name="money" value="{{$eggs}}" class="form-control name" placeholder="输入 年限可提取鸡蛋数" readonly>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="btn-group pull-left">
                            <button type="reset" class="btn btn-warning">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>

    </script>
@endsection
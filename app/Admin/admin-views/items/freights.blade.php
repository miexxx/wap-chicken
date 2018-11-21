@extends('admin::layouts.main')

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">运费列表</h3>




                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>运费规则</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                        @foreach($freights as $freight)
                            <tr>
                                <td>{{ $freight->id }}</td>
                                <td>运费{{ $freight->freight }}元满{{$freight->free}}免运费</td>
                                <td>{{ $freight->created_at }}</td>
                                <td>
                                    <a href="{{ route('admin::freights.edit', $freight->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-id="{{ $freight->id }}" class="grid-row-delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $freights->links('admin::widgets.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('admin::js.grid-row-delete', ['url' => route('admin::freights.index')])

    <script>
        $("#filter-modal .submit").click(function () {
            $("#filter-modal").modal('toggle');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
    </script>
@endsection
@extends('admin::layouts.main')

@section('content')
    <div class="row">
        <section class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#overview" data-toggle="tab" aria-expanded="false">订单概况</a>
                    </li>
                    <li>
                        <a href="#items" data-toggle="tab" aria-expanded="true">普通商品列表</a>
                    </li>
                    <li>
                        <a href="#stageitems" data-toggle="tab" aria-expanded="true">分期配送商品列表</a>
                    </li>
                    @if($order->address)
                    <li class="">
                        <a href="#express" data-toggle="tab" aria-expanded="true">物流信息</a>
                    </li>
                    @endif
                    <li class="pull-right">
                        <a class="btn btn-sm btn-default form-history-back"><i class="fa fa-arrow-left"></i>&nbsp;返回</a>
                    </li>
                </ul>
                <div class="tab-content no-padding">
                    <div class="tab-pane active" id="overview">
                        <table class="table">
                            <tr>
                                <th>订单编号</th>
                                <td>{{ $order->sn }}</td>
                            </tr>
                            <tr>
                                <th>下单时间</th>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                            <tr>
                                <th>订单状态</th>
                                <td>{{ $order->status_text }}</td>
                            </tr>
                            <tr>
                                <th>支付时间</th>
                                <td>{{ $order->paid_at }}</td>
                            </tr>
                            <tr>
                                <th>订单金额</th>
                                <td>{{ $order->items_price }}</td>
                            </tr>
                            <tr>
                                <th>运费</th>
                                <td>{{ $order->freight }}</td>
                            </tr>
                            <tr>
                                <th>应付款</th>
                                <td>{{ $order->payable_price }}</td>
                            </tr>
                            <tr>
                                <th>优惠卷</th>
                                <td>@if(isset($order->coupon))满{{$order->coupon->base_money}}减{{$order->coupon->money}}@else无@endif</td>
                            </tr>
                            <tr>
                                <th>实付款</th>
                                <td>{{ $order->price }}</td>
                            </tr>
                        </table>
                    </div>


                    <div class="tab-pane" id="items">
                        <table class="table">
                            <tr>
                                <th>图片</th>
                                <th>标题</th>
                                <th>数量</th>
                                <th>销售价</th>
                                <th>总价</th>
                                <th>提货方式</th>
                            </tr>
                            @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                            @foreach($order->items as $item)
                                <tr>
                                    <td>{!! $itemPresenter->cover($item->item) !!}</td>
                                    <td>{{ $item->item->title }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->price * $item->count }}</td>
                                    <td><span class="label-success">@if($item->type == 0)普通配送 @elseif($item->type == 1)认养@elseif($item->type == 2)存入仓库
                                            @elseif($item->type == 3)分期配送
                                            @elseif($item->type == 4)钱包充值
                                            @elseif($item->type == 5)自取
                                            @endif</span></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>


                    <div class="tab-pane" id="stageitems">
                        <table class="table">
                            <tr>
                                <th>图片</th>
                                <th>标题</th>
                                <th>数量</th>
                                <th>销售价</th>
                                <th>总价</th>
                                <th>提货方式</th>
                                <th>配送数量</th>
                                <th>配送日期</th>

                            </tr>
                            @inject('itemPresenter', "App\Admin\Presenters\ItemPresenter")
                            @foreach($order->stageitems as $item)
                                <tr>
                                    <td>{!! $itemPresenter->cover($item->item) !!}</td>
                                    <td>{{ $item->item->title }}</td>
                                    <td>{{ $item->count }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->price * $item->count }}</td>
                                    <td><span class="label-primary">@if($item->type == 0)按周分期配送 @elseif($item->type == 1)按月分期配送@endif</span></td>
                                    <td>{{$item->num}}</td>
                                    <td><a class="btn btn-xs btn-default grid-expand collapsed" data-inserted="0" data-key="{{ $item->id }}" data-toggle="collapse" data-target="#grid-collapse-{{ $item->id }}" aria-expanded="false">
                                            <i class="fa fa-caret-right"></i> 详情
                                        </a>
                                        <template class="grid-expand-{{ $item->id }}">
                                            <div id="grid-collapse-{{ $item->id }}" class="collapse">
                                                <div class="box box-primary box-solid">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">分期配送详情</h3>
                                                        <div class="box-tools pull-right">
                                                        </div>
                                                    </div>
                                                    <div class="box-body" style="display: block;">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>配送时间</th>
                                                                <th>配送数量</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if($item->type == 0)
                                                            @foreach($weeks = getWeekDate($item->first_time,$item->timeset,$item->time) as $date)
                                                                <tr>
                                                                   <td>{{$date}}</td>
                                                                    <td>{{$item->num}}</td>
                                                                </tr>
                                                            @endforeach
                                                                @else
                                                                @foreach($weeks = getMonthDate($item->first_time,$item->timeset,$item->time) as $date)
                                                                    <tr>
                                                                        <td>{{$date}}</td>
                                                                        <td>{{$item->num}}</td>
                                                                    </tr>
                                                                @endforeach

                                                            @endif
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                    <div class="box-footer">
                                                        <span>订单号：{{ $order->sn }}</span>
                                                        <span style="margin-left: 10px">下单时间： {{ $order->created_at }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    @if($order->address)
                    <div class="tab-pane" id="express">
                        <table class="table">

                            <tr>
                                <th>收货地址</th>
                                <td>{{ $order->address->detail }}</td>
                            </tr>
                            <tr>
                                <th>收货人</th>
                                <td>{{ $order->address->user_name }}</td>
                            </tr>
                            <tr>
                                <th>联系电话</th>
                                <td>{{ $order->address->tel }}</td>
                            </tr>
                            <tr>
                                <th>邮编</th>
                                <td>{{ $order->address->postal_code }}</td>
                            </tr>
                            <tr>
                                <th>物流公司</th>
                                <td>{{ $order->express_type }}</td>
                            </tr>
                            <tr>
                                <th>物流单号</th>
                                <td>{{ $order->tracking_no }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('.form-history-back').on('click', function (event) {
                event.preventDefault();
                history.back();
            });
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
        })


    </script>
@endsection
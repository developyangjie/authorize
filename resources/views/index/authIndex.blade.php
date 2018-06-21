@extends('layouts.index')
@section('title')
    <title>首页</title>
@stop
@section('style')
    <style>
        .alreadysendou{
            overflow: hidden;
        }
        .alreadysendou h3{
            line-height: 60px;
        }
        .alreadysendouUl,.alreadysendouUl li{
            list-style-type: none;
        }
        .alreadysendouUl{
            overflow: hidden;
        }
        .alreadysendouUl li{
            overflow: hidden;
            margin-bottom: 20px;
            border-bottom: 1px solid #eaeaea;
        }
        .alreadysendouUl li dl dd{
            overflow: hidden;
            margin-bottom: 20px;
        }
        .alreadysendouUl li dl dd img{
            width:160px;
            height: 90px;
        }
        .alreadysendouUl .col-sm-2{
            text-align: right;
        }
        .already_page{
            text-align: center;
            padding-top: 50px;
        }
        .already_page a{
            margin-right: 10px;
        }
        .already_page span{
            margin-right: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>首页</h2>
            <ol class="breadcrumb">
                <li class="active">
                    <strong>欢迎</strong>
                </li>
            </ol>
        </div>
        <div class="col-sm-8">
            <div class="title-action">
                <a href="" class="btn btn-primary">刷新</a>
            </div>
        </div>
    </div>
    {{--<div class="middle-box text-center animated fadeInRightBig">--}}
        {{--<h3 class="font-bold">授权token</h3>--}}
        {{--<div class="error-desc">{{$AuthorizerAccessToken}}--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        {{--<span class="label label-success pull-right">Monthly</span>--}}
                        <h5>新消息</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">0</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        {{--<span class="label label-success pull-right">Monthly</span>--}}
                        <h5>净增用户人数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins" title="关注人数：{{$yesterday_fans_arr['new_subscribe_num']}}，取关人数：{{$yesterday_fans_arr['new_unsubscribe_num']}}">{{$yesterday_fans_arr['add_fans_num']}}</h1>
                        <div class="stat-percent font-bold text-success">前天 </div>
                        <small title="关注人数：{{$before_yesterday_fans_arr['new_subscribe_num']}}，取关人数：{{$before_yesterday_fans_arr['new_unsubscribe_num']}}">{{$before_yesterday_fans_arr['add_fans_num']}}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>总用户数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$total_nums}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 white-bg alreadysendou">
            <h3>已群发消息</h3>
            @if(count($history_List)>0)
            <ul class="alreadysendouUl">
                @foreach($history_List as $vo)
                <li>
                    <div class="col-sm-2">{{$vo['create_date']}}</div>
                    <div class="col-sm-10">
                        <dl>
                            @php
                            $thumb_url_arr = explode(',',$vo['thumb_url_str']);
                            $url_arr = explode(',',$vo['url_str']);
                            $title_arr = explode(',',$vo['title_str']);
                            $count_comment_arr = explode(',',$vo1['count_comment_str']);
                            @endphp
                            @foreach($thumb_url_arr as $key=>$vo1)
                            <dd>
                                <div class="col-sm-3"><img src="{{$vo1}}" alt=""></div>
                                <div class="col-sm-9">
                                    <a href="{{$url_arr[$key]}}">{{$title_arr[$key]}}</a>
                                    <p><i class="fa fa-edit">{{$count_comment_arr[$key]}}</i></p>
                                </div>
                            </dd>
                            @endforeach
                        </dl>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="already_page">
                <span id="prev_page" style="display: none" onclick="prev_page()">上一页</span>
                <span id="after_page" onclick="after_page()">下一页</span>
                <span id="page">1</span>/<span id="total_page">{{$total_page}}</span>页
            </div>
        </div>
        @endif
        <!--添加模块结束-->
    </div>
@stop
@section('script')
    <script>
        function prev_page() {
            var page = parseInt($('#page').val());
            page = page-1;
            if(page==1){
                $('#prev_page').hide();
            }
            showHistoryList(page);
        }
        function after_page() {
            var page = parseInt($('#page').val());
            var total_page = parseInt($('#total_page').val());
            page = page*1+1;
            if(page>=total_page){
                $('#after_page').hide();
            }
            showHistoryList(page);
        }
        function showHistoryList(page) {
            var total_page = parseInt($('#total_page').val());
            if(page>total_page){
                page = total_page;
            }
            if(page<1){
                page = 1;
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data:{'page':page},
                url: "{{ route('historyListPost') }}",
                type: 'POST',
                dataType:'json',
                success: function (data) {
                    if(data.length>0){
                        var html = '';
                        for(var i in data){
                            html += '<li>\n' +
                                '                    <div class="col-sm-2">'+data[i].create_date+'</div>\n' +
                                '                    <div class="col-sm-10">\n' +
                                '                        <dl>';
                            var thumb_arr = data[i].thumb_url_str.split(',');
                            var url_arr = data[i].url_str.split(',');
                            var title_arr = data[i].title_str.split(',');
                            for(var j=0;j<thumb_arr.length;j++ ){
                                html += '<dd>\n' +
                                    '                                <div class="col-sm-3"><img src="'+thumb_arr[j]+'" alt=""></div>\n' +
                                    '                                <div class="col-sm-9">\n' +
                                    '                                    <a href="'+url_arr[j]+'">'+title_arr[j]+'</a>\n' +
                                    '                                    <p><i class="fa fa-edit"></i></p>\n' +
                                    '                                </div>\n' +
                                    '                            </dd>';
                            }
                            html += '</dl>';
                            html += '</div>';
                            html += '</li>';
                        }
                        $('.alreadysendouUl').html(html);
                    }
                }
            })
        }
    </script>
@endsection
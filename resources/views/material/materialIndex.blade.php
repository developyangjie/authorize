@extends('layouts.index')
@section('title')
    <title>图文素材</title>
@stop
@section('style')
    <style>
        .news_box{
            background-color: #fff;
            width: 226px;
            margin: 10px;
            list-style: none;
            display: inline-block;
            vertical-align: top;
            /*float: left;*/
        }
        .news_box dl{
            margin-bottom: 0;
        }
        .news_box dl dt{
            padding:12px 15px;
            position: relative;
        }
        .news_box dl dt img{
            width:100%;
        }
        .news_box dl dt span{
            position: absolute;
            bottom:12px;
            left:15px;
            width:196px;
            padding:3px 10px;
            overflow: hidden;
            line-height: 20px;
            font-weight: 500;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.55);
        }
        .news_box dl dd{
            padding:12px 15px;
            /*overflow: hidden;*/
            position: relative;
            border-top: 1px solid #E4E8EB;
        }
        .news_box dl dd .tit{
            width:112px;
            line-height: 26px;
            display: inline-block;
        }
        .news_box dl dd .tit span{
            color: #333;
        }
        .news_box dl dd .pic{
            width:60px;
            display: inline-block;
        }
        .news_box dl dd .pic img{
            width: 100%;
        }
        .news_box dl dd p{
            margin:0;
        }
        .news_box dl dd .operation{
            padding-top: 10px;

        }
        .news_box dl dd .operation i{
            margin-right:10px;
            font-size: 16px;
        }


    </style>
@stop
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>素材管理</h2>
            <ol class="breadcrumb">
                <li class="active">
                    <a>图文消息</a>
                </li>
            </ol>
        </div>
        <div class="col-sm-8">
            <div class="title-action">
                <a href="" class="btn btn-primary">刷新</a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">图文消息</button>
                        <button type="button" onclick="location.href='{{route('imageIndex')}}'" class="btn btn-w-m btn-primary">图片</button>
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">语音</button>
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">视频</button>
                    </div>
                    <div>
                        <h3>图文消息（共{{$data['total_count']}}条）</h3>
                        <button type="button" onclick="location.href='{{route('newsAdd')}}'" class="btn btn-w-m btn-info">新建图文消息</button>
                    </div>
                    <ul style="padding-left: 0px">
                        @foreach($data['item'] as $k=>$v)
                        @php
                            $thumb_url_str = '';
                            $url_str = '';
                            $title_str = '';
                        @endphp
                        <li class="news_box">
                            <dl>
                                @foreach($v['content']['news_item'] as $k1=>$v1)
                                    @if($k1==0)
                                <dt>
                                    <a href="{{$v1['url']}}" target="_blank">
                                    <div class="pic">
                                        <img class="thumb" src="{{$v1['thumb_url']}}" alt="">
                                    </div>
                                    <span>{{$v1['title']}}</span>
                                    </a>
                                </dt>
                                    @else
                                        <dd>
                                            <a target="_blank" href="{{$v1['url']}}">
                                            <div class="tit">
                                                <span>{{$v1['title']}}</span>
                                            </div>
                                            <div class="pic">
                                                <img class="thumb" src="{{$v1['thumb_url']}}" alt="">
                                            </div>
                                            </a>
                                        </dd>
                                    @endif
                                    @php
                                        $thumb_url_str .= $v1['thumb_url'].',';
                                        $url_str .= $v1['url'].',';
                                        $title_str .= $v1['title'].',';
                                    @endphp
                                @endforeach
                                @php
                                    $thumb_url_str = rtrim($thumb_url_str,',');
                                    $url_str = rtrim($url_str,',');
                                    $title_str = rtrim($title_str,',');
                                @endphp
                                <dd>
                                    <p>更新于{{showDate($v['update_time'])}}</p>
                                    <div class="operation">
                                        {{--<i style="cursor: pointer;" title="群发" class="fa fa-paper-plane-o" onclick="sendAll('{{$v['media_id']}}','{{$thumb_url_str}}','{{$url_str}}','{{$title_str}}')" ></i>--}}
                                        <i style="cursor: pointer;" title="编辑" class="fa fa-edit" onclick="edit('{{$v['media_id']}}',this)"></i>
                                        <i style="cursor: pointer;" title="删除" class="fa fa-trash" onclick="del('{{$v['media_id']}}')"></i>
                                        <i style="cursor: pointer;" title="预览" class="fa fa-play-circle-o" onclick="previewWx('{{$v['media_id']}}')"></i>
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle" aria-expanded="false">群发 <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:;" onclick="sendAll('{{$v['media_id']}}','{{$thumb_url_str}}','{{$url_str}}','{{$title_str}}')">立即群发</a></li>
                                                <li><a href="javascript:;" onclick="timeSend('{{$v['media_id']}}','{{$thumb_url_str}}','{{$url_str}}','{{$title_str}}')">定时群发</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                        </li>
                        @endforeach
                    </ul>
                    <div class="text-center">
                        <div class="btn-group">
                            @if($page>1)
                            <button class="btn btn-white" onclick="location.href='{{route('materialIndex',['page'=>($page-1)])}}'" type="button"><i class="fa fa-chevron-left"></i></button>
                            @endif
                            @for($i=1;$i<=$total_page;$i++)
                            <button class="btn btn-white @if($page==$i)active @endif" @if($page!=$i) onclick="location.href='{{route('materialIndex',['page'=>$i])}}'" @endif>{{$i}}</button>
                            @endfor
                            @if($page<$total_page)
                            <button class="btn btn-white" onclick="location.href='{{route('materialIndex',['page'=>($page+1)])}}'" type="button"><i class="fa fa-chevron-right"></i> </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h2></h2>
@stop
@section('script')
    <script>
        //编辑
        function edit(id,obj) {
            var thumb_url_str = '';
            $(obj).parent().parent().parent().find('.thumb').each(function (i,item) {
                thumb_url_str += $(item).attr('src')+'|';
                console.log(thumb_url_str);
            })
            thumb_url_str1 = thumb_url_str.substring(0,thumb_url_str.length-1);
            location.href='{{route('editNews')}}'+'?media_id='+id+'&thumb_url='+thumb_url_str1;
        }
        //预览
        function previewWx(media_id) {
            swal({
                title: '请填写要预览微信号',
                input: 'text',
                showCancelButton: true,
                confirmButtonText: '确定',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !swal.isLoading()
            }).then((result) => {
                console.log(`${result}`);
                if (result) {
                    var wx_name = `${result}`;
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('previewNews')}}",
                        type: 'POST', //GET
                        data: {
                            media_id: media_id,
                            wx_name:wx_name
                        },
                        success: function (data, textStatus, jqXHR) {
                            if (data.code == '200') {
                                swal(
                                    '提示',
                                    '发送成功',
                                    'success'
                                )
                            } else {
                                swal(
                                    '提示',
                                    data.msg.errmsg,
                                    'error'
                                )
                            }
                        },
                        error: function (err, textStatus) {
                            swal('提示','数据异常', 'error');
                        }
                    })
                }
            })
        }
        function sendAll(media_id,thumb_url_str,url_str,title_str) {
            swal({
                title: '你确定群发吗?',
                text: "本次操作无法撤销",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确定',
                cancelButtonText: '取消'
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('sendAllNews')}}",
                        type: 'POST', //GET
                        data: {
                            media_id: media_id,
                            thumb_url_str: thumb_url_str,
                            url_str: url_str,
                            title_str: title_str
                        },
                        success: function (data, textStatus, jqXHR) {
                            if (data.code == '200') {
                                swal({
                                    title: data.msg,
                                }).then(function () {
                                    window.location.reload();
                                })
                            } else {
                                swal(
                                    '提示',
                                    data.msg,
                                    'error'
                                )
                            }
                        },
                        error: function (err, textStatus) {
                            swal('提示','数据异常', 'error');
                        }
                    })
                }
            })
        }
        $(document).on('change','select[name=day]',function () {
            var hour = $(this).val();
            var hour_html = '';
            if(hour==1){
                for (var i=0;i<24;i++){
                    hour_html += '<option value="'+i+'">'+i+'</option>\n';
                }
            }else{
                var myDate = new Date();
                var now_hour = myDate.getHours();
                for (var i=now_hour;i<24;i++){
                    hour_html += '<option value="'+i+'">'+i+'</option>\n';
                }
            }
            $('select[name=hour]').html(hour_html);
        })
        //定时群发
        function timeSend(media_id,thumb_url_str,url_str,title_str) {
            var hour_html = '';
            var myDate = new Date();
            var now_hour = myDate.getHours();
            for (var i=now_hour;i<24;i++){
                hour_html += '<option value="'+i+'">'+i+'</option>\n';
            }
            var min_html = '';
            for (var j=0;j<60;j++){
                min_html += '<option value="'+j+'">'+j+'</option>\n';
            }
            swal({
                html:'<div class="form-group" id="title"></div><div class="form-group">' +
                     '<label class="col-sm-2 control-label">发送时间:</label>\n' +
                     '<div class="col-sm-10">\n' +
                     '<div class="row">\n' +
                     '<div class="col-md-4">' +
                     '<select class="form-control" name="day">\n' +
                     '                                        <option value="0">今天</option>\n' +
                     '                                        <option value="1">明天</option>\n' +
                     '                                    </select>\n' +
                     '</div>\n' +
                     '<div class="col-md-2">' +
                     '<select class="form-control" name="hour">\n' +
                     hour_html+
                     '                                    </select>\n' +
                     '</div>\n' +
                     '<label class="col-md-1 control-label">时</label>\n' +
                     '<div class="col-md-2">' +
                     '<select class="form-control" name="min">\n' +
                     min_html+
                     '                                    </select>\n' +
                     '</div>\n' +
                     '<label class="col-md-1 control-label">分</label>' +
                     '</div>\n' +
                     '</div>\n' +
                     '</div>',
                showCancelButton: true,
                width:'800px',
                confirmButtonText: '定时群发',
                onOpen:function () {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('hadTaskNews')}}",
                        type: 'POST', //GET
                        data: {
                            media_id: media_id
                        },success: function (data, textStatus, jqXHR) {
                            if (data.code == 200) {
                                var send_time = data.data*1000;
                                $('#title').text('你已经设定过该任务').css('color','red');
                                var nowDate = new Date();
                                var nowday = nowDate.getDay();
                                var myDate = new Date(send_time);
                                var day = myDate.getDay();
                                var hour = myDate.getHours();
                                var minute = myDate.getMinutes();
                                console.log(hour);
                                console.log(minute);
                                if(nowday!=day){
                                    $('select[name=day]').val(1);
                                }
                                $('select[name=hour]').val(hour);
                                $('select[name=min]').val(minute);
                            }
                        }
                    })
                }
            }).then(function (isConfirm) {
                var day = $("select[name=day]").val();
                var hour = $("select[name=hour]").val();
                var min = $("select[name=min]").val();
                if (isConfirm) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('timeSendNews')}}",
                        type: 'POST', //GET
                        data: {
                            media_id: media_id,
                            thumb_url_str: thumb_url_str,
                            url_str: url_str,
                            title_str: title_str,
                            day: day,
                            hour: hour,
                            min: min,
                        },
                        success: function (data, textStatus, jqXHR) {
                            if (data.code == '200') {
                                swal({
                                    title: data.msg,
                                    animation: false,
                                    customClass: 'animated tada'
                                }).then(function () {
                                })
                            } else {
                                swal(
                                    '提示',
                                    data.msg,
                                    'error'
                                )
                            }
                        },
                        error: function (err, textStatus) {
                            swal('提示','数据异常', 'error');
                        }
                    })
                }
            })
        }
        //删除
        function del(media_id) {
            swal({
                title: '是否删除?',
                text: "本次操作无法撤销",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确定',
                cancelButtonText: '取消'
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        url: "{{route('materialDel')}}",
                        type: 'POST', //GET
                        data: {
                            media_id: media_id
                        },
                        success: function (data, textStatus, jqXHR) {
                            if (data.code == '200') {
                                swal({
                                    title: data.msg,
                                    animation: false,
                                    customClass: 'animated tada'
                                }).then(function () {
                                    window.location.reload();
                                })
                            } else {
                                swal(
                                    '提示',
                                    data.msg,
                                    'error'
                                )
                            }
                        },
                        error: function (err, textStatus) {
                            swal('提示','数据异常', 'error');
                        }
                    })
                }
            })
        }
    </script>
@stop

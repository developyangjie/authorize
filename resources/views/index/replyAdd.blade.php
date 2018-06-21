@extends('layouts.index')
@section('title')
    <title>添加回复</title>
@stop
@section('style')
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
    <style>
        #content_div .fa{
            margin-left:10px;font-size:18px; color: #a94442; cursor: pointer;
        }
        #content_div .fa-edit{
            color: #397332;
        }
        #content_div .fa-trash{
            color: #a94442;
        }
        #content_div .fa-plus-square-o{
            color: #000;
        }
        #content_div .reply_type_words{
            margin-left: 30px;
            font-size: 18px;
            font-weight: 600;
        }
        #content_div .content_box{
            border: 1px dashed grey;
            margin-bottom: 10px;
        }
    </style>
@stop
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>回复管理</h2>
            <ol class="breadcrumb">
                <li>
                    <a>回复管理</a>
                </li>
                <li class="active">
                    <a>@if(isset($res))
                            编辑回复
                        @else
                            添加回复：
                        @endif</a>
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
                        @if(isset($res))
                            编辑回复
                            @else
                            添加回复：
                        @endif
                    </div>
                    <div class="ibox-content">
                        <form id="form1" method="post" action="{{route('replyAddPost')}}" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">关键词：</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b">
                                        <div class="input-group-btn">
                                            <input type="hidden" name="match_type" id="match_type" value="@if(isset($res)){{$res['match_type']}}@else 0 @endif">
                                            <button data-toggle="dropdown" id="match_show" class="btn btn-white dropdown-toggle" type="button">@if(isset($res) && $res['match_type']==1)半匹配@else全匹配@endif<span class="caret"></span></button>
                                            <ul class="dropdown-menu" id="match_select">
                                                <li><a value="0" href="#">全匹配</a></li>
                                                <li><a value="1" href="#">半匹配</a></li>
                                            </ul>
                                        </div>
                                        <input type="text" class="form-control" name="key_word" value="@if(isset($res)){{$res['key_word']}}@endif"></div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">添加新回复：</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b chosen-select" name="reply_type" id="reply_type">
                                        <option value="">请选择类型</option>
                                        <option value="text">文字</option>
                                        <option value="image">图片</option>
                                        <option value="voice">语音</option>
                                        <option value="video">视频</option>
                                        <option value="news">图文</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label"></label>
                                <div class="col-sm-10" id="type_show">
                                    <button type="button" id="add_new" class="btn btn-w-m btn-info">添加</button>
                                    {{--<button type="button" id="history_select" class="btn btn-w-m btn-success">从历史消息选择</button>--}}
                                </div>
                            </div>
                            @if(isset($res))
                                <input type="hidden" name="id" value="{{$res['id']}}">
                            @endif
                            <div class="hr-line-dashed"></div>
                            <div class="form-group" id="content_div">
                                @if(isset($res))
                                    @foreach($res['reply_content'] as $vo)
                                            <label class="col-sm-2 control-label"></label>
                                        @if($vo['reply_type']=='text')
                                            <div reply_type="text" reply_id="{{$vo['item']['id']}}" class="col-sm-10 content_box">
                                                <div style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">
                                                    <span class="reply_text" title="{{$vo['item']['reply_text']}}">{{my_substr($vo['item']['reply_text'],25)}}</span>
                                                    <span class="reply_type_words">{{$vo['reply_type_words']}}</span>
                                                    <i class="fa fa-edit" onclick="edit(this)" title="编辑"></i>
                                                    <i class="fa fa-trash" onclick="del(this)" title="删除"></i>
                                                </div>
                                            </div>
                                        @elseif($vo['reply_type']=='news')
                                            <div reply_type="news" class="col-sm-10 content_box">
                                                @foreach($vo['item'] as $news_vo)
                                                <div reply_id="{{$news_vo['id']}}" style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">
                                                    <span class="title" title="{{$news_vo['title']}}">{{my_substr($news_vo['title'],15)}}</span>
                                                    <span class="reply_type_words">{{$vo['reply_type_words']}}</span>
                                                    <i class="fa fa-search" onclick="look(this)" title="查看"></i>
                                                    <i class="fa fa-edit" onclick="edit(this)" title="编辑"></i>
                                                    <i class="fa fa-trash" onclick="del(this)" title="删除"></i>
                                                    @if($loop->index==0)
                                                    <i class="fa fa-plus-square-o" onclick="add(this)" title="添加"></i>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <input type="hidden" name="reply_id_str" value="">
                                    <button class="btn btn-white" type="button" onclick="location.href='{{route('replyIndex')}}'">取消</button>
                                    <button class="btn btn-primary" id="submit1">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="{{ URL::asset('assets/admin/js/plugins/dataTables/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/plugins/dataTables/dataTables.tableTools.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/plugins/dataTables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/plugins/dataTables/dataTables.responsive.js') }}"></script>
    <script>
        $(function () {
            $('#type_show').hide();
            $("#reply_type").change(function () {
                var type = $(this).val();
                if(type!=''){
                    $('#type_show').show();
                }else{
                    $('#type_show').hide();
                }
            })
            $('#match_select a').click(function () {
                var _textval = $(this).text();
                var _val = $(this).attr('value');
                var _html = _textval+"<span class='caret'></span>";
                $('#match_show').html(_html);
                $('#match_type').val(_val);
            })
            //点击添加新素材
            $(document).on('click','#add_new',function () {
                var type = $('#reply_type').val();
                if(type=='text'){
                    swal({
                        html:'<textarea class="form-control" name="reply_text" id="reply_text" cols="5" rows="5" placeholder="请输入要回复的内容"></textarea>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '确定',
                        cancelButtonText: '取消'
                    }).then((result) => {
                        if (result) {
                            var reply_text = $('#reply_text').val();
                            $.ajax({
                                data:{reply_text:reply_text},
                                type:'post',
                                url:'{{route('addTextMaterialPost')}}',
                                dataType : 'json',
                                headers : {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success:function (res) {
                                    if(res.code==200){
                                        var show_html = '<label class="col-sm-2 control-label"></label>'+
                                            '<div reply_type="text" reply_id="'+res.text_id+'" class="col-sm-10 content_box">' +
                                            '<div style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">' +
                                            '<span class="reply_text" title="'+reply_text+'">'+subString(reply_text, 25, 1)+'</span>' +
                                            '<span class="reply_type_words">文字</span>' +
                                            '<i class="fa fa-edit" onclick="edit(this)" title="删除"></i>' +
                                            '<i class="fa fa-trash" onclick="del(this)" title="编辑"></i>' +
                                            '</div>'
                                        '</div>';
                                        $('#content_div').append(show_html);
//                                    $('#content_div').after('<div class="hr-line-dashed"></div>');
                                    }else{
                                        swal(
                                            '提示',
                                            '加入历史消息失败',
                                            'error'
                                        )
                                    }
                                },error:function (err) {

                                }
                            })
                        }
                    })
                }else if(type=='news'){
                    var news_html = '<form action="" id="form2" onsubmit="return false;" class="form-horizontal">\n' +
                        '                                <div class="form-group">\n' +
                        '                                    <label class="col-sm-2 control-label">标题：</label>\n' +
                        '                                    <div class="col-sm-8">\n' +
                        '                                        <input type="text" id="news_title" class="form-control" name="title">\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="form-group">\n' +
                        '                                    <label class="col-sm-2 control-label">描述：</label>\n' +
                        '                                    <div class="col-sm-8">\n' +
                        '                                        <input type="text" class="form-control" name="description">\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="form-group">\n' +
                        '                                    <label class="col-sm-2 control-label">图片地址：(必须为腾讯系下面的：)</label>\n' +
                        '                                    <div class="col-sm-8">\n' +
                        '                                        <input type="text" class="form-control" name="pic_url">\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="form-group">\n' +
                        '                                    <label class="col-sm-2 control-label">跳转链接：</label>\n' +
                        '                                    <div class="col-sm-8">\n' +
                        '                                        <input type="text" class="form-control" name="url">\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                            </form>';
                    swal({
                        html:news_html,
                        width:800,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '确定',
                        cancelButtonText: '取消'
                    }).then((result) => {
                        if (result) {
                            var news_title = $('#news_title').val();
                            $.ajax({
                                data:$('#form2').serialize(),
                                type:'post',
                                url:'{{route('addNewsMaterialPost')}}',
                                dataType : 'json',
                                headers : {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success:function (res) {
                                    if(res.code==200){
                                        var show_html = '<label class="col-sm-2 control-label"></label>'+
                                            '<div reply_type="news" class="col-sm-10 content_box">' +
                                            '<div reply_id="'+res.news_id+'" style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">' +
                                            '<span class="title" title="'+news_title+'">'+subString(news_title,15,1)+'</span>' +
                                            '<span class="reply_type_words">图文</span>' +
                                            '<i class="fa fa-search" onclick="look(this)" title="查看"></i>' +
                                            '<i class="fa fa-plus-square-o" onclick="add(this)" title="添加"></i>' +
                                            '<i class="fa fa-edit" onclick="edit(this)" title="删除"></i>' +
                                            '<i class="fa fa-trash" onclick="del(this)" title="编辑"></i>' +
                                            '</div>'
                                        '</div>';
                                        $('#content_div').append(show_html);
//                                    $('#content_div').after('<div class="hr-line-dashed"></div>');
                                    }else{
                                        swal(
                                            '提示',
                                            '添加失败',
                                            'error'
                                        )
                                    }
                                },error:function (err) {
                                    console.log(err);
                                    if(err.status == 422){
                                        $(err.responseJSON.errors).each(function(idx,item){
                                            for(var key in item){
                                                swal('提示', item[key][0], 'error');
                                                return false;
                                            }
                                            return false;
                                        })
                                    }
                                }
                            })
                        }
                    })
                }
            })
            //从历史消息选择
            {{--$(document).on('click','#history_select',function () {--}}
                {{--var reply_type = $('#reply_type').val();--}}
                {{--switch(reply_type){--}}
                    {{--case `text`:--}}
                        {{--var _history_select = '<div class="ibox-content">'--}}
                            {{--+'<table class="table table-striped table-bordered table-hover" id="history_message" >'--}}
                            {{--+'<thead>'--}}
                            {{--+'<tr>'--}}
                            {{--+'<th width="20%">ID</th>'--}}
                            {{--+'<th width="60%">内容</th>'--}}
                            {{--+'<th width="20%">操作</th>'--}}
                            {{--+'</tr>'--}}
                            {{--+'</thead>'--}}
                            {{--+'<tbody>'--}}
                            {{--+'</tbody>'--}}
                            {{--+'</table>'--}}
                            {{--+'</div>';--}}
                        {{--swal({--}}
                            {{--width:'800px',--}}
                            {{--html: _history_select,--}}
                        {{--});--}}
                        {{--$('#history_message').dataTable({--}}
                            {{--destroy: true,--}}
                            {{--processing: true,--}}
                            {{--ordering: false,--}}
                            {{--searching: false,--}}
                            {{--serverSide:true,--}}
                            {{--// lengthMenu: [20, 50, 100, 200, 1000],--}}
                            {{--columns: [--}}
                                {{--{"data": "id"},--}}
                                {{--{"data": "reply_text"},--}}
                                {{--{"data": ""}--}}
                            {{--],--}}
                            {{--columnDefs: [--}}
                                {{--{--}}
                                    {{--targets: 2,--}}
                                    {{--render: function (data, type, row) {--}}
                                        {{--return "<button class=\"btn btn-primary\" onclick='selectReply(this)'>选择</button>";--}}
                                    {{--}--}}
                                {{--}--}}
                            {{--],--}}
                            {{--ajax: {--}}
                                {{--headers: {--}}
                                    {{--'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
                                {{--},--}}
                                {{--url: "{{route('getAllReply')}}",--}}
                                {{--type: 'POST', //GET--}}
                                {{--data: function (d) {--}}
                                    {{--d.reply_type = 'text';--}}
                                    {{--return d;--}}
                                {{--},--}}
                                {{--//dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text--}}
                                {{--error: function (err, textStatus) {--}}
        {{--//                        error_msg(err);--}}
                                {{--}--}}
                            {{--},--}}
                            {{--language: {--}}
                                {{--url: "{{ URL::asset('assets/admin/js/plugins/dataTables/zh-cn.json') }}"--}}
                            {{--}--}}
                        {{--});--}}
                        {{--break;--}}
                    {{--case `news`:--}}
                        {{--var _history_select = '<div class="ibox-content">'--}}
                            {{--+'<table class="table table-striped table-bordered table-hover" id="history_message" >'--}}
                            {{--+'<thead>'--}}
                            {{--+'<tr>'--}}
                            {{--+'<th width="10%">ID</th>'--}}
                            {{--+'<th width="25%">标题</th>'--}}
                            {{--+'<th width="25%">描述</th>'--}}
                            {{--+'<th width="25%">链接地址</th>'--}}
                            {{--+'<th width="15%">操作</th>'--}}
                            {{--+'</tr>'--}}
                            {{--+'</thead>'--}}
                            {{--+'<tbody>'--}}
                            {{--+'</tbody>'--}}
                            {{--+'</table>'--}}
                            {{--+'</div>';--}}
                        {{--swal({--}}
                            {{--width:'800px',--}}
                            {{--html: _history_select,--}}
                        {{--});--}}
                        {{--$('#history_message').dataTable({--}}
                            {{--destroy: true,--}}
                            {{--processing: true,--}}
                            {{--ordering: false,--}}
                            {{--searching: false,--}}
                            {{--serverSide:true,--}}
                            {{--// lengthMenu: [20, 50, 100, 200, 1000],--}}
                            {{--columns: [--}}
                                {{--{"data": "id"},--}}
                                {{--{"data": "title"},--}}
                                {{--{"data": "description"},--}}
                                {{--{"data": "url"},--}}
                                {{--{"data": ""}--}}
                            {{--],--}}
                            {{--columnDefs: [--}}
                                {{--{--}}
                                    {{--targets: -1,--}}
                                    {{--render: function (data, type, row) {--}}
                                        {{--return "<button class=\"btn btn-primary\" onclick='selectReply(this)'>选择</button>";--}}
                                    {{--}--}}
                                {{--}--}}
                            {{--],--}}
                            {{--ajax: {--}}
                                {{--headers: {--}}
                                    {{--'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
                                {{--},--}}
                                {{--url: "{{route('getAllReply')}}",--}}
                                {{--type: 'POST', //GET--}}
                                {{--data: function (d) {--}}
                                    {{--d.reply_type = 'news';--}}
                                    {{--return d;--}}
                                {{--},--}}
                                {{--//dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text--}}
                                {{--error: function (err, textStatus) {--}}
                                    {{--//                        error_msg(err);--}}
                                {{--}--}}
                            {{--},--}}
                            {{--language: {--}}
                                {{--url: "{{ URL::asset('assets/admin/js/plugins/dataTables/zh-cn.json') }}"--}}
                            {{--}--}}
                        {{--});--}}
                        {{--break;--}}
                {{--}--}}
            {{--})--}}
            //提交
            $('#submit1').click(function () {
                swal.enableLoading();
                var reply_id_str = "";
                $('#content_div').find('.content_box').each(function (i,item) {
                    var reply_type=$(item).attr('reply_type');
                    if(reply_type=='text'){
                        var reply_id=$(item).attr('reply_id');
                        reply_id_str+=reply_type+'|'+reply_id+','
                    }else if(reply_type=='news'){
                        var news_id_str = ''
                        $(item).children('div').each(function (j,item1) {
                            var reply_id=$(item1).attr('reply_id');
                            news_id_str +=reply_id+';';
                        })
                        news_id_str=news_id_str.substr(0,news_id_str.length-1);
                        reply_id_str+=reply_type+'|'+news_id_str+','
                    }
                })
                reply_id_str=reply_id_str.substr(0,reply_id_str.length-1);
                console.log(reply_id_str);
                $('input[name=reply_id_str]').val(reply_id_str);
                $('#form1').ajaxForm({
                    dataType : 'json',
                    headers : {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success : function (res) {
                        if(res.code == '200'){
                            $(".swal2-container").remove();
                            swal('提示', res.msg, 'success').then(function () {
                                swal.close(function () {
                                    window.location.href='{{route('replyIndex')}}';
                                });
                            });
                        }else{
                            $(".swal2-container").remove();
                            swal('提示', res.msg, 'error');
                        }
                    },error : function (err) {
                        $(".swal2-container").remove();
                        console.log(err.responseJSON.errors);
                        if(err.status == 422){
                            $(err.responseJSON.errors).each(function(idx,item){
                                for(var key in item){
                                    swal('提示', item[key][0], 'error');
                                    return false;
                                }
                                return false;
                            })
                        }
                    }
                });
            })
        })
        function edit(obj) {
            var reply_type = $(obj).parent().parent().attr('reply_type');
            console.log(reply_type);
            switch(reply_type){
                case 'text':
                    var reply_id = $(obj).parent().parent().attr('reply_id');
                    var before_reply_text = $(obj).parent().parent().find('.reply_text').attr('title');
                    swal({
                        html:'<textarea class="form-control" name="reply_text" id="reply_text" cols="5" rows="5" placeholder="请输入要回复的内容">'+before_reply_text+'</textarea>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '确定',
                        cancelButtonText: '取消'
                    }).then((result) => {
                        if (result) {
                            var reply_text = $('#reply_text').val();
                            $.ajax({
                                data:{reply_text:reply_text,reply_id:reply_id},
                                type:'post',
                                url:'{{route('editTextMaterialPost')}}',
                                dataType : 'json',
                                headers : {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                success:function (res) {
                                    if(res.code==200){
                                        $(obj).parent().find('.reply_text').text(subString(reply_text,25,1));
                                        $(obj).parent().find('.reply_text').attr('title',reply_text);
                                    }else{
                                        swal(
                                            '提示',
                                            res.msg,
                                            'error'
                                        )
                                    }
                                },error:function (err) {
                                    console.log(err.responseJSON.errors);
                                    if(err.status == 422){
                                        $(err.responseJSON.errors).each(function(idx,item){
                                            for(var key in item){
                                                swal('提示', item[key][0], 'error');
                                                return false;
                                            }
                                            return false;
                                        })
                                    }
                                }
                            })
                        }
                    })
                    break;
                case 'news':
                    var reply_id = $(obj).parent().attr('reply_id');
                    $.ajax({
                        data:{reply_type:'news',reply_id:reply_id},
                        type:'post',
                        url:'{{route('getReplyDetail')}}',
                        dataType : 'json',
                        headers : {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success:function (res) {
                                var news_html = '<form action="" id="form2" onsubmit="return false;" class="form-horizontal">\n' +
                                    '<input type="hidden" name="reply_id" value="'+res.id+'">'+
                                    '                                <div class="form-group">\n' +
                                    '                                    <label class="col-sm-2 control-label">标题：</label>\n' +
                                    '                                    <div class="col-sm-8">\n' +
                                    '                                        <input type="text" id="news_title" class="form-control" name="title" value="'+res.title+'">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="form-group">\n' +
                                    '                                    <label class="col-sm-2 control-label">描述：</label>\n' +
                                    '                                    <div class="col-sm-8">\n' +
                                    '                                        <input type="text" class="form-control" name="description" value="'+res.description+'">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="form-group">\n' +
                                    '                                    <label class="col-sm-2 control-label">图片地址：(必须为腾讯系下面的：)</label>\n' +
                                    '                                    <div class="col-sm-8">\n' +
                                    '                                        <input type="text" class="form-control" name="pic_url" value="'+res.pic_url+'">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                                <div class="form-group">\n' +
                                    '                                    <label class="col-sm-2 control-label">跳转链接：</label>\n' +
                                    '                                    <div class="col-sm-8">\n' +
                                    '                                        <input type="text" class="form-control" name="url" value="'+res.url+'">\n' +
                                    '                                    </div>\n' +
                                    '                                </div>\n' +
                                    '                            </form>';
                                swal({
                                    html:news_html,
                                    width:800,
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: '确定',
                                    cancelButtonText: '取消'
                                }).then((result) => {
                                    var new_title = $('#news_title').val();
                                    if (result) {
                                        var reply_text = $('#reply_text').val();
                                        $.ajax({
                                            data:$('#form2').serialize(),
                                            type:'post',
                                            url:'{{route('editNewsMaterialPost')}}',
                                            dataType : 'json',
                                            headers : {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            success:function (res1) {
                                                if(res1.code==200){
                                                    $(obj).parent().find('.title').attr('title',new_title);
                                                    $(obj).parent().find('.title').text(subString(new_title, 15, 1));
                                                }else{
                                                    swal(
                                                        '提示',
                                                        res1.msg,
                                                        'error'
                                                    )
                                                }
                                            },error:function (err) {
                                                console.log(err.responseJSON.errors);
                                                if(err.status == 422){
                                                    $(err.responseJSON.errors).each(function(idx,item){
                                                        for(var key in item){
                                                            swal('提示', item[key][0], 'error');
                                                            return false;
                                                        }
                                                        return false;
                                                    })
                                                }
                                            }
                                        })
                                    }
                                })
                        },error:function (err) {
                            console.log(err.responseJSON.errors);
                            if(err.status == 422){
                                $(err.responseJSON.errors).each(function(idx,item){
                                    for(var key in item){
                                        swal('提示', item[key][0], 'error');
                                        return false;
                                    }
                                    return false;
                                })
                            }
                        }
                    })
                    break;
            }
        }
        function look(obj) {
            var reply_type = $(obj).parent().parent().attr('reply_type');
            var reply_id = $(obj).parent().attr('reply_id');
            switch(reply_type){
                case 'news':
                    $.ajax({
                        data:{reply_type:'news',reply_id:reply_id},
                        type:'post',
                        url:'{{route('getReplyDetail')}}',
                        dataType : 'json',
                        headers : {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success:function (res) {
                            var news_html = '<form action="" id="form2" onsubmit="return false;" class="form-horizontal">\n' +
                                '<input type="hidden" name="reply_id" value="'+res.id+'">'+
                                '                                <div class="form-group">\n' +
                                '                                    <label class="col-sm-2 control-label">标题：</label>\n' +
                                '                                    <div class="col-sm-8">\n' +
                                '                                        <input type="text" id="news_title" class="form-control" name="title" value="'+res.title+'">\n' +
                                '                                    </div>\n' +
                                '                                </div>\n' +
                                '                                <div class="form-group">\n' +
                                '                                    <label class="col-sm-2 control-label">描述：</label>\n' +
                                '                                    <div class="col-sm-8">\n' +
                                '                                        <input type="text" class="form-control" name="description" value="'+res.description+'">\n' +
                                '                                    </div>\n' +
                                '                                </div>\n' +
                                '                                <div class="form-group">\n' +
                                '                                    <label class="col-sm-2 control-label">图片地址：(必须为腾讯系下面的：)</label>\n' +
                                '                                    <div class="col-sm-8">\n' +
                                '                                        <input type="text" class="form-control" name="pic_url" value="'+res.pic_url+'">\n' +
                                '                                    </div>\n' +
                                '                                </div>\n' +
                                '                                <div class="form-group">\n' +
                                '                                    <label class="col-sm-2 control-label">跳转链接：</label>\n' +
                                '                                    <div class="col-sm-8">\n' +
                                '                                        <input type="text" class="form-control" name="url" value="'+res.url+'">\n' +
                                '                                    </div>\n' +
                                '                                </div>\n' +
                                '                            </form>';
                            swal({
                                html:news_html,
                                width:800,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '确定'
                            })
                        }
                    });
                    break;
            }
        }
        function add(obj) {
            var news_html = '<form action="" id="form2" onsubmit="return false;" class="form-horizontal">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label class="col-sm-2 control-label">标题：</label>\n' +
                '                                    <div class="col-sm-8">\n' +
                '                                        <input type="text" id="news_title" class="form-control" name="title">\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                                <div class="form-group">\n' +
                '                                    <label class="col-sm-2 control-label">描述：</label>\n' +
                '                                    <div class="col-sm-8">\n' +
                '                                        <input type="text" class="form-control" name="description">\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                                <div class="form-group">\n' +
                '                                    <label class="col-sm-2 control-label">图片地址：(必须为腾讯系下面的：)</label>\n' +
                '                                    <div class="col-sm-8">\n' +
                '                                        <input type="text" class="form-control" name="pic_url">\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                                <div class="form-group">\n' +
                '                                    <label class="col-sm-2 control-label">跳转链接：</label>\n' +
                '                                    <div class="col-sm-8">\n' +
                '                                        <input type="text" class="form-control" name="url">\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                            </form>';
            swal({
                html:news_html,
                width:800,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确定',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result) {
                    var news_title = $('#news_title').val();
                    $.ajax({
                        data:$('#form2').serialize(),
                        type:'post',
                        url:'{{route('addNewsMaterialPost')}}',
                        dataType : 'json',
                        headers : {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success:function (res) {
                            if(res.code==200){
                                var show_html = '<div reply_id="'+res.news_id+'" style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">' +
                                    '<span class="title" title="'+news_title+'">'+subString(news_title,15,1)+'</span>' +
                                    '<span class="reply_type_words">图文</span>' +
                                    '<i class="fa fa-search" onclick="look(this)" title="查看"></i>' +
                                    '<i class="fa fa-edit" onclick="edit(this)" title="删除"></i>' +
                                    '<i class="fa fa-trash" onclick="del(this)" title="编辑"></i>' +
                                    '</div>';
                                $(obj).parent().parent().append(show_html);
//                                    $('#content_div').after('<div class="hr-line-dashed"></div>');
                            }else{
                                swal(
                                    '提示',
                                    '添加失败',
                                    'error'
                                )
                            }
                        },error:function (err) {
                            console.log(err.responseJSON.errors);
                            if(err.status == 422){
                                $(err.responseJSON.errors).each(function(idx,item){
                                    for(var key in item){
                                        swal('提示', item[key][0], 'error');
                                        return false;
                                    }
                                    return false;
                                })
                            }
                        }
                    })
                }
            })
        }
        //选择历史消息
//        function selectReply(obj) {
//            var reply_type = $('#reply_type').val();
//            var reply_id = $(obj).parent().parent().find('td').eq(0).text();
//            console.log(reply_id);
//            switch(reply_type){
//                case `text`:
//                    var reply_text = $(obj).parent().parent().find('td').eq(1).text();
//                    var show_html = '<label class="col-sm-2 control-label"></label>'+
//                        '<div reply_type="text" reply_id="'+reply_id+'" class="col-sm-10 content_box">' +
//                        '<div style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">' +
//                        '<span class="reply_text" title="'+reply_text+'">'+subString(reply_text, 25, 1)+'</span>' +
//                        '<span class="reply_type_words">文字</span>' +
//                        '<i class="fa fa-edit" onclick="edit(this)" title="删除"></i>' +
//                        '<i class="fa fa-trash" onclick="del(this)" title="编辑"></i>' +
//                        '</div>'
//                    '</div>';
//                    break;
//                case `news`:
//                    var news_title = $(obj).parent().parent().find('td').eq(1).text();
//                    var show_html = '<label class="col-sm-2 control-label"></label>'+
//                        '<div reply_type="news" reply_id="'+reply_id+'" class="col-sm-10 content_box">' +
//                        '<div style="line-height: 25px;border-bottom: 1px solid #ccc;font-size:14px;">' +
//                        '<span class="title" title="'+news_title+'">'+subString(news_title, 15, 1)+'</span>' +
//                        '<span class="reply_type_words">图文</span>' +
//                        '<i class="fa fa-search" onclick="look(this)" title="查看"></i>' +
//                        '<i class="fa fa-edit" onclick="edit(this)" title="删除"></i>' +
//                        '<i class="fa fa-trash" onclick="del(this)" title="编辑"></i>' +
//                        '</div>'
//                    '</div>';
//                    break;
//            }
//            console.log(show_html);
//            $('#content_div').append(show_html);
//            swal.close();
//        }
        //删除
        function del(obj) {
            var reply_type = $(obj).parent().parent().attr('reply_type');
            if(reply_type=='news'){
                var _div = $(obj).parent();
                console.log(_div.parent().parent().children('div').length);
                if(_div.parent().children('div').length==1){
                    _div.parent().remove();
                }else{
                    _div.remove();
                }
            }else if(reply_type=='text'){
                var _div = $(obj).parent().parent();
                _div.prev().remove();
                _div.remove();
            }
        }
        //截取字符串 包含中文处理
        //(串,长度,增加...)
        function subString(str, len, hasDot){
            var newLength = 0;
            var newStr = "";
            var chineseRegex = /[^\x00-\xff]/g;
            var singleChar = "";
            var strLength = str.replace(chineseRegex,"**").length;
            for(var i = 0;i < strLength;i++)
            {
                singleChar = str.charAt(i).toString();
                if(singleChar.match(chineseRegex) != null)
                {
                    newLength += 2;
                }
                else
                {
                    newLength++;
                }
                if(newLength > len)
                {
                    break;
                }
                newStr += singleChar;
            }

            if(hasDot && strLength > len)
            {
                newStr += "...";
            }
            return newStr;
        }
    </script>
@stop
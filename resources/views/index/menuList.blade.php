@extends('layouts.index')
@section('title')
    <title>首页</title>
@stop
@section('style')
    <style>
        *{margin:0;padding:0;font-size:12px;font-family:'Microsoft YaHei';list-style: none;}
        .menu_bottom{
            margin:auto;
            width:100%;
            height:40px;
            border: 1px solid #e7e7eb;
        }
        .menu_bottom .par_menu{
            text-align:center;
            color:#fff;
            float: left;
            line-height: 40px;
            position: relative;
            cursor: pointer;
        }
        .menu_bottom .par_menu span{
            display: block;
            font-size:14px;
            width: 100%;
            border-right: 1px solid #e7e7eb;
            color: #666;
        }
        .menu_bottom .par_menu em {
            background-color: #c0c0c0;
            color: #fff;
            height: 17px;
            line-height: 16px;
            border-radius: 10px;
            padding: 0 4px;
            font-style: normal;
            margin-right: 5px;
            position: absolute;
            top: 11px;
            left: 5px;
        }
        .menu_bottom .par_menu:last-child span{
            border:0px ;
        }
        .screen,.menu_control{
            margin: 30px;
            width: 300px;
            height: 500px;
            border: 1px solid #ccc;
        }
        .screen{
            float: left;
            background-color: #fff;
            position: relative;
        }
        .menu_control{
            width: 550px;
            background-color: #f5f5f9;
            float: left;
            display: none;
        }
        .menu_bottom{
            height:40px;
            background-color:#fbfbfb;
            position: absolute;
            bottom: 0px;
        }
        .sub_menu{
            position: absolute;
            width: 100%;
            bottom:52px;
            left: 0px;
            color: #fff;
            border: 1px solid #e7e7eb;
        }
        .sub_menu li{
            height: 38px;
            min-width:100% ;
            font-size:14px;
            line-height: 14px;
            text-align: center;
            padding: 10px;
            color: #666;
            background-color:#fbfbfb;
            cursor: pointer;
        }
        .sub_menu li:not(.add){
            border-bottom: 1px solid #ccc;
        }
        .par_menu span.box_selected{
            background-color:#fff;
            color:green;
            border:1px solid green;
            height: 39px;
        }
        .sub_menu li.box_selected{
            background-color:#fff;
            color:green;
            border:1px solid green;
        }
        .menu_bottom .par_menu .add{
            color: #666;
            line-height: 34px;
        }
        .menu_bottom .par_menu .sub_menu .add{
            line-height: 16px;
        }
        .sub_menu li.triangle {
            position: absolute;
            bottom: -11px;
            left: 45%;
            width: 0;
            height: 0;
            border-top: 10px solid #e7e7eb;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            min-width:0;
            padding:0;
            border-bottom:0;
            background-color:transparent;
        }
        /*这是第二波*/
        .add_menu_tit{
            width:96%;
            margin:auto;
            overflow: hidden;
            line-height:40px;
            border-bottom:1px solid #e7e7eb;
        }
        .add_menu_tit a{
            float: right;
            text-decoration: none;
            color: #4e699a;
        }
        dl.add_menu_ul{
            width:96%;
            margin:auto;
            overflow: hidden;
            padding-top: 20px;
        }
        dl.add_menu_ul dd{
            margin:0;
            line-height: 30px;
        }
        dl.add_menu_ul dd span{
            display: inline-block;
            width:80px;
        }
        dl.add_menu_ul dd input[type='text']{
            height: 26px;
            width:300px;
            padding:0 6px;
            border:1px solid #e7e7eb;
            background-color: #fff;
        }
        dl.add_menu_ul dd p{
            margin:0;
            padding-left: 86px;
            color: #999;
            line-height: 30px;
        }
        dl.add_menu_ul dd input[type="radio"]{
            margin:0;
            margin-right:6px;
            vertical-align:sub;
        }
        dl.add_menu_ul dd label{
            line-height: 30px;
            padding-right:20px;
        }
        dl.add_menu_ul .menu_setting_main{
            margin:20px auto;
            background-color: #fff;
            width:98%;
            padding:10px 0 20px 0;
            border:1px solid #e7e7eb;
            display: none;
        }
        dl.add_menu_ul .menu_setting_main textarea{
            width:96%;
            padding-left:2%;
            border:0;
            resize: none;
            outline: none;
        }
        dl.add_menu_ul .menu_setting_main p{
            margin:0 10px;
            color: #999;
            line-height: 23px;
            padding-left: 0;
        }

        dl.add_menu_ul .menu_setting_main div.link_add span {
            display: inline-block;
            width: 80px;
        }
        dl.add_menu_ul .menu_setting_main div.link_add{
            padding-left: 10px;
        }
        dl.add_menu_ul .menu_setting_main div.link_add input[type='text'] {
            height: 26px;
            width: 300px;
            padding: 0 6px;
            border: 1px solid #e7e7eb;
            background-color: #fff;
        }
        /*选择图文样式*/
        .news_box{
            background-color: #fff;
            width: 177px;
            margin: -5px 10px;
            list-style: none;
            display: inline-block;
            vertical-align: top;
            border:1px solid #e7e7eb;
            /*float: left;*/
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
            left:14px;
            width:147px;
            padding:3px 10px;
            overflow: hidden;
            line-height: 20px;
            font-weight: 500;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.55);
        }
        .news_box dl dd{
            padding:3px 15px;
            overflow: hidden;
            position: relative;
            border-top: 1px solid #E4E8EB;
        }
        .news_box dl dd .tit{
            width:76px;
            line-height: 23px;
            float: left;
        }
        .news_box dl dd .tit span{
            color: #333;
        }
        .news_box dl dd .pic{
            width:57px;
            float: right;
        }
        .news_box dl dd .pic img{
            width: 100%;
        }
        .news_box dl dd p{
            margin:0;
        }
    </style>
    <link rel="stylesheet" href="{{asset('/assets/admin/css/jquery-ui.min.css')}}">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>菜单列表</h2>
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
        <div style="overflow: hidden;">
            <div class="screen">
                <ul class="menu_bottom" id="drag_par_menu">
                    {{--<div style="font-size: 35px;margin-left: 50%;">+</div>--}}
                    <li class="par_menu not_me" style="width: 100%">
                        <span class="add" title="最多添加3个一级菜单" onclick="addPreMenu(this)" style="font-size: 30px;">+</span>
                    </li>
                </ul>
            </div>
            <div class="menu_control">
                <div class="add_menu_tit">
                    <span>菜单名称</span>
                    <a id="del_menu" href="javascript:;">删除菜单</a>
                </div>
                {{----}}
                <dl class="add_menu_ul">
                    <dd>
                        <span>菜单名称</span>
                        <input type="text" id="menu_name">
                    </dd>
                    <dd><p>字数不超过4个汉字或8个字母</p></dd>
                    <dd id="menu_setting">
                        <div class="menu_setting_tit">
                            <span>菜单内容</span>
                            <input type="hidden" id="menu_level" value="1">
                            <input type="hidden" id="level" value="level1">
                            <input type="hidden" id="per_index" value="0">
                            <input type="hidden" id="sub_index" value="0">
                            <label for="click"><input type="radio" id="click" name="type" value="click">发送消息</label>
                            <label for="view"><input type="radio" id="view" name="type" value="view">跳转网页</label>
                            <label for="media_id"><input type="radio" id="media_id" name="type" value="media_id">发送图文</label>
                            <label for="miniprogram"><input type="radio" id="miniprogram" name="type" value="miniprogram">跳转小程序</label>
                        </div>
                        <div class="menu_setting_main" id="message_box">
                            <textarea name="" id="message" cols="30" name="message"></textarea>
                        </div>
                        <div class="menu_setting_main" id="url_box">
                            <p>订阅者点击该子菜单会跳转到以下链接</p>
                            <div class="link_add"><span>页面地址</span><input type="text" id="url" name="url"></div>
                        </div>
                        <div class="menu_setting_main" id="news_list_box">
                            <div class="news_box">
                            </div>
                            <input type="hidden" name="news_media_id" value="">
                            <input type="hidden" name="news_html" value="">
                            <button type="button" id="news_select" class="btn btn-xs btn-warning">选择图文消息</button>
                        </div>
                    </dd>
                    <button type="button" id="save" class="btn btn-w-m btn-primary">保存</button>
                </dl>
                {{----}}
            </div>
        </div>
        <div style="text-align: center; width:60%;padding-bottom:100px;">
            <input type="hidden" name="menu_list_json" id="menu_list_json" value="{{$menu_list_json}}">
            <button type="button" id="submit1" class="btn btn-w-m btn-primary">提交至微信</button>
        </div>
@stop
@section('script')
    <script src="{{asset('/assets/admin/js/jquery-ui-1.10.4.min.js')}}"></script>
    <script>
        $(function () {
            showMenus();
            //点击主菜单
            $(document).on('click','.par_menu span:not(.add)',function () {
                $('.menu_control').show();
                $(this).addClass('box_selected');
                $(this).parent().siblings('li').find('span').removeClass('box_selected');
                $('.sub_menu').hide();
                $(this).parent().find('.sub_menu').show();
                $(this).parent().find('.sub_menu li').removeClass('box_selected');
                $(this).parent().find('.sub_menu ul').sortable({
                    items:">li:not(.not_me)"
                });
                var menu_name = $(this).parent().attr('menu_name');
                var par_index = $(this).parent().index();
                $('#menu_name').val(menu_name);
                $('#menu_level').val(1);
                $('#per_index').val(par_index);
                var level = $(this).parent().attr('level');
                $('#level').val(level);
                if(level=='level2'){
                    $('#menu_setting').hide();
                }else{
                    $('#menu_setting').show();
                    menu_control_show($(this).parent());
                }
            })
            //点击子菜单
            $(document).on('click','.sub_menu li:not(.add)',function () {
                $('.menu_control').show();
                $(this).addClass('box_selected');
                console.log($(this).parent().parent());
                $(this).parent().parent().parent().find('span').removeClass('box_selected');
                $(this).siblings('li').removeClass('box_selected');
                var menu_name = $(this).text();
                var per_index = $(this).parent().parent().parent().index();
                var sub_index = $(this).index();
                $('#menu_name').val(menu_name);
                $('#menu_level').val(2);
                $('#per_index').val(per_index);
                $('#sub_index').val(sub_index);
                $('#menu_setting').show();
                console.log($(this).attr('property'));
                menu_control_show($(this));
            })
            $(document).on('click','input[name=type]',function () {
                console.log($(this).val())
                var _type = $(this).val();
                if(_type=='click'){
                    $('#message_box').siblings().not('.menu_setting_tit').hide();
                    $('#message_box').show();
                }else if(_type=='view'){
                    $('#url_box').siblings().not('.menu_setting_tit').hide();
                    $('#url_box').show();
                }else if(_type=='media_id'){
                    $('#news_list_box').show();
                    $('#news_list_box').siblings().not('.menu_setting_tit').hide();
                }
            })
            //选择图文
            $('#news_select').click(function () {
                swal({
                    width: 800,
                    title:'请选择图文素材',
                    html:'<div style="width: 760px;">' +
                    '<ul id="list_box">' +
                    '</ul>' +
                    '</div>'+
                    '<div class="page"></div>'
                })
                getNewList(1);
            })
            //确定
            $('#save').click(function () {
                var menu_name = $('#menu_name').val();
                var menu_level = $('#menu_level').val();
                var level = $('#level').val();
                var per_index = $('#per_index').val();
                if(menu_name==''){
                    alert('请输入菜单名称');
                    return false;
                }
                //代表是一级菜单有子菜单
                if(menu_level==1 && level=='level2'){
                    $('.par_menu').eq(per_index).find('span').text(menu_name);
                    $('.par_menu').eq(per_index).attr('menu_name',menu_name);
                    alert('success');
                    return true;
                }

                var sub_index = $('#sub_index').val();
                var type = $('input[name=type]:checked').val();
                var property_json = {'type':'','name':menu_name};
                console.log(type);
                var text = '';
                if(type=='click'){
                    property_json.type='click';
                    text = $('#message').val();
                    if(text==''){
                        alert('请输入内容');
                        return false;
                    }
                    var key = "v"+"0"+menu_level+"0"+per_index+"0"+sub_index+type;
                    property_json.key=key;
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        url: "{{ route('addKeyText') }}",
                        data: {"key":key,"text":text},
                        type: "post",
                        success:function(res){
                            alert(res.msg)
                        },
                        error:function(err){
                            alert(err)
                        }
                    })
                }else if(type=='view'){
                    var url = $('#url').val();
                    if(url==''){
                        alert('请输入网址');
                        return false;
                    }
                    property_json.type='view';
                    property_json.url=url;
                    alert('success')
                }else if(type=='media_id'){
                    var media_id = $('input[name=news_media_id]').val();
                    var news_html =$('input[name=news_html]').val();
                    property_json.type = 'media_id';
                    property_json.media_id = media_id;
                    alert('success');
                }else{
                    alert('请选择类型');
                    return false;
                }
                var property_str=JSON.stringify(property_json);
                console.log(property_str);
                if(menu_level==1){
                    $('.par_menu').eq(per_index).attr('property',property_str);
                    $('.par_menu').eq(per_index).find('span').text(subString(menu_name,8,1));
                    $('.par_menu').eq(per_index).attr('menu_name',menu_name);
                    if(type=='click'){
                        $('.par_menu').eq(per_index).attr('text',text);
                    }else if(type=='media_id'){
                        $('.par_menu').eq(per_index).attr('news_html',news_html);
                    }
                }else{
                    $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).attr('property',property_str);
                    $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).text(subString(menu_name,14,1));
                    $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).attr('menu_name',menu_name);
                    if(type=='click'){
                        $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).attr('text',text);
                    }else if(type=='media_id'){
                        $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).attr('news_html',news_html);
                    }
                }
                return true;
            })
            //提交
            $('#submit1').click(function () {
                var json_str = '{"button":[';
                $('.par_menu').each(function (i,item) {
                    var level = $(item).attr('level');
                    console.log(level);
                    if(level=='level1'){
                        json_str+=$(item).attr('property')+',';
                    }else if(level=='level2'){
                        json_str+='{"name":"'+$(item).attr('menu_name')+'","sub_button":[';
                        var str = '';
                        $(item).find('.sub_menu li').each(function (j,item1) {
                            if($(item1).attr('property')){
                                str+=$(item1).attr('property')+',';
                            }
                        })
                        json_str += str.substr(0,str.length-1);
                        json_str += ']},'
                    }
                })
                json_str = json_str.substr(0,json_str.length-1);
                json_str += ']}';
                console.log(json_str);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: "{{ route('createMenuList') }}",
                    data: {"menu_json_str":json_str},
                    type: "post",
                    success:function(res){
                        alert(res.msg)
                    },
                    error:function(err){

                    }
                })
            })
            //拖拽
            $("#drag_par_menu").sortable({
                items:">li:not(.not_me)"
            });
        })
        //获取图文列表
        function getNewList(page) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url: "{{ route('newsListPost') }}",
                data: {"page":page},
                type: "post",
                success:function(res){
                    if(res.code=='200'){
                        var _html = '';
                        var total_page = res.msg.total_page;
                        $(res.msg.item).each(function (i,item) {
                            _html +='<li class="news_box">\n' +
                                '           <dl>\n';
                            $(item.content.news_item).each(function (j,item1) {
                                if(j==0){
                                    _html +=   '                                 <dt>\n' +
                                        '                                            <a href="'+item1.url+'" target="_blank">\n' +
                                        '                                                <div class="pic">\n' +
                                        '                                                    <img class="thumb" src="'+item1.thumb_url+'" alt="">\n' +
                                        '                                                </div>\n' +
                                        '                                                <span>'+item1.title+'</span>\n' +
                                        '                                            </a>\n' +
                                        '                                        </dt>\n';
                                }else{
                                    _html +=    '                                <dd>\n' +
                                        '                                            <a target="_blank" href="'+item1.url+'">\n' +
                                        '                                                <div class="tit">\n' +
                                        '                                                    <span>'+item1.title+'</span>\n' +
                                        '                                                </div>\n' +
                                        '                                                <div class="pic">\n' +
                                        '                                                    <img class="thumb" src="'+item1.thumb_url+'" alt="">\n' +
                                        '                                                </div>\n' +
                                        '                                            </a>\n' +
                                        '                                        </dd>\n';
                                }
                            });
                            _html +=     '                               <dd>\n' +
                                '                                            <div class="operation">\n' +
                                '                                                <button type="button" id="sure" media_id="'+item.media_id+'" class="btn btn-xs btn-warning">选择</button>\n' +
                                '                                            </div>\n' +
                                '                                        </dd>\n' +
                                '                                    </dl>\n' +
                                '                                </li>';
                        })
//                        console.log(_html);
                        $('#list_box').html(_html);
                        var prev_page = page-1;
                        var after_page = page+1;
                        if(page>1 && page<total_page){
                            var page_html = '<a class="prev_page" onclick="getNewList('+prev_page+')" href="javascript:;">上一页</a><a class="next_page" onclick="getNewList('+after_page+')" href="javascript:;">下一页</a>';
                        }else if(page==1 && page<total_page){
                            var page_html = '<a class="next_page" onclick="getNewList('+after_page+')" href="javascript:;">下一页</a>';
                        }else if(page>1 && page>=total_page){
                            var page_html = '<a class="prev_page" onclick="getNewList('+prev_page+')" href="javascript:;">上一页</a>';
                        }
                        $('.page').html(page_html);
                    }else{
                        console.log(res.msg);
                    }
                },
                error:function(err){
                    console.log(err);
                }
            })
        }
        //选择素材
        $(document).on('click','#sure',function () {
            var _html = $(this).parent().parent().parent().parent().html();
            $('#news_list_box .news_box').html(_html);
            $('#news_list_box .news_box').find('.operation').parent().remove();
            swal.close();
            var media_id = $(this).attr('media_id');
            var news_html = $('#news_list_box .news_box').html();
            $('input[name=news_media_id]').val(media_id);
            $('input[name=news_html]').val(news_html);
//            console.log(_html);
        })
        //控制界面显示数据
        function menu_control_show(obj) {
            console.log(obj.attr('property'));
            if(obj.attr('property')){
                var json_obj = JSON.parse(obj.attr('property'));
                var type = json_obj.type;
                console.log(type);
                if(type=='click'){
                    var text = obj.attr('text');
                    $('#click').prop('checked',true);
                    $('#message_box').siblings().not('.menu_setting_tit').hide();
                    $('#message_box').show();
                    $('#message').val(text);
                }else if(type=='view'){
                    var url = json_obj.url;
                    $('#view').prop('checked',true);
                    $('#url_box').siblings().not('.menu_setting_tit').hide();
                    $('#url_box').show();
                    $('#url').val(url);
                }else if(type=='media_id'){
                    var news_html = obj.attr('news_html');
                    var media_id = json_obj.media_id;
                    $('#media_id').prop('checked',true);
                    $('#news_list_box').show();
                    $('#news_list_box').siblings().not('.menu_setting_tit').hide();
                    $('input[name=news_media_id]').val(media_id);
                    $('input[name=news_html]').val(news_html);
                    $('#news_list_box .news_box').html(news_html);
                }else{
                    $('input[name=type]').prop('checked',false);
                    $('#message_box').hide();
                    $('#url_box').hide();
                    $('#news_list_box').hide();
                }
            }
        }
        //添加主菜单
        function addPreMenu(obj) {
            var par_length = $('.par_menu').length;
            if(par_length<=3){
                var menu_name = '菜单名称';
                $(obj).parent().siblings('li').find('span').removeClass('box_selected');
                $('.sub_menu').hide();

                var par_menu_html = "<li class=\"par_menu\" level='level1' menu_name=\""+menu_name+"\"><span>"+menu_name+"</span><div class=\"sub_menu\" style=\"display: none;\"><ul><li class=\"add not_me\" onclick=\"addSubMenu(this)\" style=\"font-size: 30px;\">+</li><li class=\"triangle not_me\"></li></ul></div></li>";
                $(obj).parent().before(par_menu_html);
                if(par_length==3){
                    $(obj).parent('.par_menu').remove();
                }
                setWidth();
                $('#menu_name').val(menu_name);
                $('#menu_level').val(1);
                $('#per_index').val(par_length-1);
                $('input[name=type]').prop('checked',false);
                $('#message').val('');
                $('#url').val('');
                $('.menu_setting_main').hide()
            }
        }
        //添加子菜单菜单
        function addSubMenu(obj) {
            var sub_length = $(obj).parent().find('li:not(.triangle)').length;
            $(obj).parent().parent().parent().find('span').removeClass('box_selected');
            $(obj).siblings('li').removeClass('box_selected');
            if(sub_length<=5){
                var menu_name = '子菜单名称';
                if(sub_length==1){
                    if(!confirm('添加子菜单会清空一级菜单的信息，确定添加子菜单吗？')){
                        return false
                    }
                    $(obj).parent().parent().parent().children('span').prepend('<em>=</em>');
                    $(obj).parent().parent().parent().attr('level','level2');
                    $(obj).parent().parent().parent().attr('property','');
                    $(obj).parent().parent().parent().attr('text','');
                }
                var par_menu_html = "<li class='box_selected'>"+menu_name+"</li>";
                $(obj).before(par_menu_html);
                if(sub_length==5){
                    $(obj).remove();
                }
                var per_index = $(obj).parent().parent().parent().index();
                var sub_index = sub_length-1;
                $('#menu_name').val(menu_name);
                $('#menu_level').val(2);
                $('#per_index').val(per_index);
                $('#sub_index').val(sub_index);
                $('#menu_setting').show();
                $('input[name=type]').prop('checked',false);
                $('#message').val('');
                $('#url').val('');
                $('.menu_setting_main').hide()
            }
        }
        //删除菜单
        $('#del_menu').click(function () {
            if(confirm('删除菜单后，菜单下所有信息都会删除')){
                var per_index = $('#per_index').val();
                var sub_index = $('#sub_index').val();
                var menu_level = $('#menu_level').val();
                if(menu_level==1){
                    $('.par_menu').eq(per_index).remove();
                    $('.menu_control').hide();
                    if(!$('.par_menu').hasClass('not_me')){
                        var _add_html = '<li class="par_menu not_me">\n' +
                            '                        <span class="add" title="最多添加3个一级菜单" onclick="addPreMenu(this)" style="font-size: 30px;">+</span>\n' +
                            '                    </li>';
                        $('.menu_bottom').append(_add_html);
                    }
                    setWidth();
                }else{
                    $('.par_menu').eq(per_index).find('.sub_menu li').eq(sub_index).remove();
                    $('.menu_control').hide();
                }
            }
        })
        //设置菜单宽度
        function setWidth() {
            var par_length = $('.par_menu').length;
            var _width = 100/par_length+'%';
            $('.par_menu').css({'width':_width});
        }
        function showMenus() {
            var par_button_str = $('#menu_list_json').val();
            if(par_button_str!=''){
                var par_button = JSON.parse(par_button_str).button;
                var par_button_html = '';
                if(par_button.length>0){
                    if(par_button.length<=2){
                        var _width = 100/(par_button.length+1)+'%';
                    }else{
                        var _width = 100/par_button.length+'%';
                    }
                    for(var i in par_button){
                        var sub_button = par_button[i].sub_button;
                        //有子菜单
                        if(sub_button && sub_button.length>0){
                            par_button_html +='<li class="par_menu" level="level2" menu_name="'+par_button[i].name+'" style="width:'+_width+' ;"><em>=</em><span>'+subString(par_button[i].name,8,1)+'</span>';
                        }else{
                            par_button_html +='<li class="par_menu" menu_name="'+par_button[i].name+'" level="level1" property="{';
                            var str='';
                            for(var t in par_button[i]){
                                if(t!='sub_button' && t!='text' && t!='news_html')
                                str += '&quot;'+t+'&quot;:&quot;'+par_button[i][t]+'&quot;,';
                            }
                            par_button_html +=str.substr(0,str.length-1);
                            par_button_html +='}"';
                            //文字
                            if(par_button[i]['text']!=undefined ){
                                par_button_html += ' text='+par_button[i]['text'];
                            }
                            //图文
                            if(par_button[i]['news_html']!=undefined ){
                                console.log(par_button[i]['news_html']);
                                par_button_html += ' news_html='+stringToEntity(par_button[i]['news_html']);
                            }
                            par_button_html +=' style="width:'+_width+' ;"><span>'+subString(par_button[i].name,8,1)+'</span>';
                        }
                        par_button_html +='<div class="sub_menu" style="display: none;"><ul>';
                        if(sub_button && sub_button.length>0){
                            for(var j in sub_button){
                                par_button_html += '<li property="{';
                                var str='';
                                for(var t in sub_button[j]){
                                    if(t!='sub_button' && t!='text' && t!='news_html')
                                    str += '&quot;'+t+'&quot;:&quot;'+sub_button[j][t]+'&quot;,';
                                }
                                par_button_html +=str.substr(0,str.length-1);
                                par_button_html +='}"';
                                if(sub_button[j]['text']!=undefined ){
                                    par_button_html += ' text='+sub_button[j]['text'];
                                }
                                //图文
                                if(sub_button[j]['news_html']!=undefined ){
                                    par_button_html += ' news_html='+stringToEntity(sub_button[j]['news_html']);
                                }
                                par_button_html +='>'+subString(sub_button[j].name,14,1)+'</li>';
                            }
                        }
                        par_button_html +='<li class="add not_me" onclick="addSubMenu(this)" style="font-size: 30px;">+</li>';
                        par_button_html +='<li class="triangle not_me"></li>';
                        par_button_html +='<ul></div>';
                        par_button_html +="</li>";
                    }
                    if(par_button.length<=2) {
                        par_button_html += '<li class="par_menu not_me"  style="width:' + _width + ' ;">\n' +
                            '    <span class="add" title=\'最多添加3个一级菜单\' onclick="addPreMenu(this)" style="font-size: 30px;">+</span>\n' +
                            '</li>';
                    }
                }
                //            console.log(par_button_html);
                $('.menu_bottom').html(par_button_html);
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
        //字符串转实体
        function stringToEntity(str,radix){
            let arr=str.split('')
            radix=radix||0
            let tmp=arr.map(item=>
                `&#${(radix?'x'+item.charCodeAt(0).toString(16):item.charCodeAt(0))};`).join('')
            console.log(`'${str}' 转实体为 '${tmp}'`)
            return tmp
        }
    </script>
@stop    
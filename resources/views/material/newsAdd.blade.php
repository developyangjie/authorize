@extends('layouts.index')
@section('title')
    <title>添加图文</title>
@stop
@section('style')
    <link href="{{URL::asset('assets/admin/css/plugins/cropper/cropper.min.css')}}" rel="stylesheet">
    <style>
        .news_box{
            background-color: #fff;
            /*width: 200px;*/
            margin: 10px;
            list-style: none;
        }
        .news_box dl{}
        .news_box dl dt{
            padding:12px 15px;
            position: relative;
        }
        .news_box dl dt .pic{
            width:100%;
            height: 100px;
            background-image:url('');
        }
        .news_box dl dt a{
            position: absolute;
            bottom:12px;
            left:15px;
            /*width:170px;*/
            width: 90%;
            padding:3px 10px;
            overflow: hidden;
            line-height: 20px;
            font-weight: 500;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.55);
        }
        .news_box dl dd{
            padding:12px 15px;
            overflow: hidden;
            position: relative;
            border-top: 1px solid #E4E8EB;
        }
        .news_box dl dd .tit{
            width:62%;
            line-height: 26px;
            float: left;
        }
        .news_box dl dd .tit a{
            color: #333;
        }
        .news_box dl dd .pic{
            width:60px;
            float: right;
            height: 60px;
            background-image:url('');
        }
        .news_box dl dd p{
            margin:0;
        }
        .news_box dl dd .add_news{
            font-size: 55px;
            text-align: center;
            line-height: 60px;
            cursor: pointer;
        }
        .news_box dl .border_show{
            border: 1px solid green;
        }
        #image_list,#image_list li{
            list-style: none;
            margin:0;
            padding:0;
        }
        #image_list{
            overflow: hidden;
        }
        #image_list li{
            width:100px;
            height:150px;
            float: left;
            margin-right: 20px;
            margin-bottom: 20px;
        }
        #image_list li img{
            width:100%;
        }
        #image_list li p{
            line-height: 30px;
            height:30px;
            overflow: hidden;
            text-align: center;
            border:1px solid #eee;
            border-top:0;
            font-size: 14px;
            margin-bottom: 0;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        #image_list li p a{
            color: #333;
            font-weight: bold;
        }
        .page{
            text-align: center;
            line-height:22px;
            margin-top: 20px;
        }
        .page a{
            font-size: 12px;
            display:inline-block;
            border:1px solid #eee;
            padding:0 6px;
            margin-right:20px;
            color: #666;
        }
        .page a:hover{
            background: #337ab7;
            color: #fff;
            border-color:#337ab7;
        }
        /*正文图片选择
         */
        #content_img_box ul{
            list-style: none;
        }
        #content_img_box li{
            width:150px;
            height: 100px;
            margin: 10px;
            padding: 5px;
            float: left;
        }
        #content_img_box img{
            max-width: 100%;
        }
        #triangle-topright,span.close_1{
            position:absolute;
            right:0;
            top:0;
        }
        #triangle-topright{
            width: 0;
            height: 0;
            border-top:30px solid #000;
            border-left:30px solid transparent;
            opacity: 0.7;
        }
        span.close_1{
            z-index: 2;
            color: #ffffff;
            right: 2px;
            top: -2px;
            cursor: pointer;
            font-size: 18px;
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
                        添加图文消息
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox">
                    <div class="ibox-content float-e-margins news_box" style="padding: 0px;">
                        <dl>
                            <dt class="border_show">
                                <div class="pic">
                                </div>
                                <a href="javascript:;" class="tit">标题</a>
                            </dt>
                            <dd class="add_box">
                                <div class="add_news">+</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="ibox float-e-margins">
                    <div class="ibox-content controll_box">
                        <form action="" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题：</label>
                            <div class="col-sm-10">
                                <input type="text" id="title" class="form-control" value="标题">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">作者：</label>
                            <div class="col-sm-10">
                                <input type="text" id="author" class="form-control">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">正文：</label>
                            <div class="col-sm-10">
                                <script id="content" type="text/plain" style="height: 500px;"></script>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">原文链接：</label>
                            <div class="col-sm-10">
                                <input type="text" id="content_source_url" class="form-control">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">封面：</label>
                            <div class="col-sm-10">
                                <img id="cover_img" src="" alt="">
                                <button id="img_content_btn" type="button" class="btn btn-white">从正文选择</button>
                                <button id="img_btn" type="button" class="btn btn-white">从图库选择</button>
                                <button id="upload_img_btn" type="button" class="btn btn-white">上传图片</button>
                                <input type="file" name="image" accept="image/gif,image/jpeg,image/png" style="display: none;">
                            </div>
                            <div class="row" id="crop_box" style="margin-left:30px;display:none;">
                                <div class="col-md-6">
                                    <div class="image-crop">
                                        <img id="pre_crop_img" src="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="img-preview img-preview-sm"></div>
                                    <div class="btn-group">
                                        <label title="Donload image" id="download" class="btn btn-primary">确定</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">摘要：</label>
                            <div class="col-sm-10">
                                <textarea name="" id="digest" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">留言：</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="need_open_comment">
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="radio" checked name="only_fans_can_comment" value="0">所有人可留言
                                <input type="radio" name="only_fans_can_comment" value="1">仅关注后可留言
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-white" type="button" onclick="location.href='{{route('materialIndex')}}'">取消</button>
                                <button type="button" class="btn btn-primary" id="save">保存</button>
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
    <script src="{{asset('/assets/admin/js/plugins/cropper/cropper.min.js') }}"></script>
    <script src="{{asset('/assets/admin/ueditor/1.4.3/ueditor.config.js') }}"></script>
    <script src="{{asset('/assets/admin/ueditor/1.4.3/ueditor.all.js') }}"></script>
    <script src="{{asset('/assets/admin/ueditor/1.4.3/lang/zh-cn/zh-cn.js') }}"></script>
    <script>
        $(function () {
            $('#upload_img_btn').click(function () {
                $('input[name=image]').click();
            })
            var $image = $(".image-crop > img");
            var $inputImage = $('input[name=image]');
            if (window.FileReader) {
                $inputImage.change(function() {
                    var fileReader = new FileReader(),
                        files = this.files,
                        file;

                    if (!files.length) {
                        return;
                    }

                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $('#crop_box').show();
                            var $image = $(".image-crop > img");
                            var _index = $('.news_box .border_show').index();
                            console.log(_index);
                            if(_index==0){
                                var Ratio = 16/9;
                            }else{
                                var Ratio = 1;
                            }
                            if($image.attr('src')){
                                $image.cropper("reset", true).cropper("replace", this.result).cropper('aspectRatio',Ratio);
                            }else{
                                $('#pre_crop_img').attr('src',this.result);
                                crop_img(Ratio);
                            }
//                            $image.cropper("reset", true).cropper("replace", this.result);
                        };
                    } else {
                        showMessage("Please choose an image file.");
                    }
                });
            }

            //上传裁剪过的封面图到素材
            $("#download").click(function() {
                var $image = $(".image-crop > img");
                var data_url = $image.cropper("getDataURL");
                swal.enableLoading();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: "{{ route('saveCropImg') }}",
                    data: {'img_data_url':data_url},
                    type: "post",
                    success:function(res){
                        swal.hideLoading();
                        swal.close();
                        if(res.code=='200'){
                            var media_id = res.msg.media_id;
                            var url = res.msg.url;
                            $('#cover_img').attr('src',url).css({'width':'160px','height':'90px'}).show();
                            $('.news_box .border_show').find('.pic').css('background-image',"url("+url+")").css('background-size','100% 100%');
                            var json_str = $('.news_box .border_show').attr('news_json');
                            var json_obj = JSON.parse(json_str);
                            json_obj.thumb_media_id = media_id;
                            json_obj.thumb_url = url;
                            $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
                            $('#crop_box').hide();
                        }
                        console.log(res);
                    },error:function () {
                        swal.hideLoading();
                        swal.close();
                    }
                })
            });
            //初始化
            //实例化编辑器
            //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
            var ue = UE.getEditor('content');
            var empty_json = {
                "title": "标题",//标题
                "author": "",//作者
                "digest": "",//图文消息的摘要
                "content": "",//内容
                "content_source_url": "",//图文消息的原文地址
                "thumb_media_id": "",//	图文消息的封面图片素材id
                "thumb_url": "",//	图文消息的封面图片素材url
                "show_cover_pic": 0,//是否显示封面，0为false，
                "url": "",//，
                "thumb_url": "",//
                "need_open_comment": 0,
                "only_fans_can_comment": 0
            };
            var empty_json_str = JSON.stringify(empty_json);
            $('.news_box dl dt').attr('news_json',empty_json_str);
            //点击图文列表事件
            $(document).on('click','.news_box dl dt,.news_box dl dd:not(.add_box)',function () {
                var content = ue.getContent();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.content = content;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));

                $(this).siblings().removeClass('border_show');
                $(this).addClass('border_show');
                var json_str = $(this).attr('news_json');
                show_data(json_str);
            })
            //点击添加图文事件
            $(document).on('click','.add_news',function () {
                var json = empty_json;
                var json_str = JSON.stringify(json);
                $(this).parent().siblings().removeClass('border_show');
                var _html = "<dd class='border_show' news_json='"+json_str+"'>\n" +
                    "                            <div class=\"tit\">\n" +
                    "                                标题\n" +
                    "                            </div>\n" +
                    "                            <div class=\"pic\">\n" +
                    "                                <img src=\"\" alt=\"\">\n" +
                    "                            </div>\n" +
                    "                            <span class=\"close_1\">×</span>\n" +
                    "                            <div id=\"triangle-topright\"></div>"+
                    "                        </dd>";
                $(this).parent().before(_html);
                show_data(json_str);
                if($('.news_box dd').length>=7){
                    $(this).remove();
                }
            })
            //标题改变
            $('#title').bind('input propertychange change',function () {
                var title = $(this).val();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.title = title;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
                $('.news_box .border_show .tit').text(title);
            })
            //作者
            $('#author').bind('input propertychange change',function () {
                var author = $(this).val();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.author = author;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            })
            ue.addListener("contentChange",function(){
                var content = ue.getContent();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.content = content;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            });
            //原文链接
            $('#content_source_url').bind('input propertychange change',function () {
                var content_source_url = $(this).val();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.content_source_url = content_source_url;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            })
            //摘要
            $('#digest').bind('input propertychange change',function () {
                var digest = $(this).val();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.digest = digest;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            })
            //是否留言
            $('#need_open_comment').click(function () {
                if($(this).is(':checked')){
                    var need_open_comment = 1;
                    $(this).parent().parent().next().show();
                }else{
                    var need_open_comment = 0;
                    $(this).parent().parent().next().hide();
                }
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.need_open_comment = need_open_comment;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            })
            //留言权限
            $('input[name=only_fans_can_comment]').change(function () {
                var only_fans_can_comment = $(this).val();
                var need_open_comment = 1;
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.need_open_comment = need_open_comment;
                json_obj.only_fans_can_comment = only_fans_can_comment;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
            })
            //显示每个数据到表单
            function show_data(json_str) {
                var json_obj = JSON.parse(json_str);
                $('#title').val(json_obj.title);
                $('#author').val(json_obj.author);
                ue.setContent(json_obj.content);
                $('#content_source_url').val(json_obj.content_source_url);
                var thumb_media_id = json_obj.thumb_media_id;
                var thumb_url = json_obj.thumb_url;
                $('#crop_box').hide();
                if(thumb_media_id!=''){
                    $('#cover_img').attr('src',thumb_url).css({'width':'160px','height':'90px'}).show();
                }else{
                    $('#cover_img').attr('src','').hide();
                }
                $('#digest').val(json_obj.digest);
                if(json_obj.need_open_comment){
                    $('#need_open_comment').prop('checked',true);
                    $('#need_open_comment').parent().parent().next('div').show();
                }else{
                    $('#need_open_comment').prop('checked',false);
                    $('#need_open_comment').parent().parent().next('div').hide();
                }
                if(json_obj.only_fans_can_comment){
                    console.log(json_obj.only_fans_can_comment);
                    $('input[name=only_fans_can_comment]').eq(1).prop('checked',true);
                }else{
                    $('input[name=only_fans_can_comment]').eq(0).prop('checked',true);
                }
            }
            $('#save').click(function () {
                var content = ue.getContent();
                var json_str = $('.news_box .border_show').attr('news_json');
                var json_obj = JSON.parse(json_str);
                json_obj.content = content;
                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
                var json_arr = [];
                $('.news_box dl').children().not('.add_box').each(function (i,item) {
                    console.log($(item));
                    var json_str = $(item).attr('news_json');
                    item_obj = JSON.parse(json_str);
                    json_arr.push(item_obj);
                })
                var json_obj = {"articles":json_arr};
                var json_str = JSON.stringify(json_obj);
                swal.enableLoading();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: "{{ route('newsAddPost') }}",
                    data: {"news_str":json_str},
                    type: "post",
                    success:function(res){
                        swal.hideLoading();
                        if(res.code=='200'){
                            swal('提示','图文素材新建成功', 'success').then(function () {
                                location.href='{{route('materialIndex')}}';
                            });
                        }else{
                            swal(res.msg)
                            console.log(res.msg);
                        }
                    },
                    error:function(err){
                        swal.hideLoading();
                        console.log(err);
                    }
                })
            })
            offset = 0;
            count = 10;
            //从素材选择封面
            $('#img_btn').click(function () {
                swal({
                    width: 620,
                    title:'请选择图片素材',
                    html:'<div style="width: 640px;">' +
                    '<ul id="image_list">' +
                    '</ul>' +
                    '</div>'+
                    '<div class="page"><a class="prev_page" onclick="prev_page()" href="javascript:;">上一页</a><a class="next_page" onclick="next_page()" href="javascript:;">下一页</a></div>'
                })
                show_img(offset,count)
             })
            //从正文选择
            $('#img_content_btn').click(function () {
                var editor_html = ue.getContent();
                var ue_jquery = $(editor_html);
                var img_html = "<div id='content_img_box'><ul>"
                ue_jquery.find('img').each(function (i,item) {
//                    var origin_cover_src = $('#cover_img').attr('src');
//                    var _src = $(item).attr('src');
//                    $('#cover_img').attr('src',_src);
//                    if($('#cover_img').width()>=150 && $('#cover_img').height()>=150){
                        img_html += '<li>';
                        img_html += '<img onclick="choose_img(this)" src="'+$(item).attr('src')+'">';
                        img_html += '</li>';
//                    }
//                    $('#cover_img').attr('src',origin_cover_src);
                })
                img_html += "</ul><div style='clear: both;'></div></div>";
                swal({
                    html:img_html,
                    width:800,
                    confirmButtonColor: '#3085d6'
                })
            })
            $(document).on('click','.close_1',function () {
//                $(this).parent().prev().addClass('border_show');
                $(this).parent().prev().click();
                $(this).parent().remove();
                return false;
            })
         })
            //选择正文图片
            function choose_img(obj) {
                var _src = $(obj).attr('src');
                $('#crop_box').show();
                var $image = $(".image-crop > img");
                var _index = $('.news_box .border_show').index();
                console.log(_index);
                if(_index==0){
                    var Ratio = 16/9;
                }else{
                    var Ratio = 1;
                }
                if($image.attr('src')){
                    $image.cropper("reset", true).cropper("replace", _src).cropper('aspectRatio',Ratio);
                }else{
                    $('#pre_crop_img').attr('src',_src);
                    crop_img(Ratio);
                }
            }
            //素材选择封面
            function select_img(obj) {
                var media_id = $(obj).attr('media_id');
                $.ajax({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: "{{ route('downImgFile') }}",
                    data: {'media_id':media_id},
                    type: "post",
                    success:function(res){
                        $('#crop_box').show();
                        var _index = $('.news_box .border_show').index();
                        if(_index==0){
                            var Ratio = 16/9;
                        }else{
                            var Ratio = 1;
                        }
                        var $image = $(".image-crop > img");
                        if($image.attr('src')){
                            $image.cropper("reset", true).cropper("replace", res).cropper('aspectRatio',Ratio);
                        }else{
                            $('#pre_crop_img').attr('src',res);
                            crop_img(Ratio);
                        }
                    }
                })
//                var url = $(obj).find('img').attr('src');
//                $('#cover_img').attr('src',url).css({'width':'160px','height':'90px'}).show();
//                $('.news_box .border_show').find('.pic').css('background-image',"url("+url+")").css('background-size','100% 100%');
//                var json_str = $('.news_box .border_show').attr('news_json');
//                var json_obj = JSON.parse(json_str);
//                json_obj.thumb_media_id = menu_id;
//                json_obj.thumb_url = url;
//                $('.news_box .border_show').attr('news_json',JSON.stringify(json_obj));
                swal.close();
            }
            function crop_img(Ratio=16/9) {
                //裁剪图片
                var $image = $(".image-crop > img");
                $image.cropper({
                    aspectRatio: Ratio,
                    preview: ".img-preview",
                    done: function(data) {
                        // Output the result data for cropping image.
                    }
                });
            }
            function prev_page() {
                offset =offset-count;
                show_img(offset,count);
            }
            function next_page() {
                offset =offset*1+count*1;
                console.log(offset);
                show_img(offset,count);
            }
            function show_img(offset,count) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    url: "{{ route('imageListPost') }}",
                    data: {"offset":offset,'count':count},
                    type: "post",
                    success:function(res){
                        if(res.code=='200'){
                            var _html='';
                            var total_num = res.msg.total_count;
                            $(res.msg.item).each(function (i,item) {
                                _html += '<li media_id="'+item.media_id+'" onclick="select_img(this)">'+
                                    '<img src="'+item.url+'" alt="'+item.name+'">' +
                                    '<p><a href="">'+item.name+'</a></p>' +
                                    '</li>';
                            })
                            $('#image_list').html(_html);
                            if(offset==0){
                                $('#image_list').parent().next().find('.prev_page').hide();
                            }else{
                                $('#image_list').parent().next().find('.prev_page').show();
                            }
                            if((offset*1+count*1)>=total_num){
                                $('#image_list').parent().next().find('.next_page').hide();
                            }else{
                                $('#image_list').parent().next().find('.next_page').show();
                            }
                        }else{
                            console.log(res.msg);
                        }
                    },
                    error:function(err){
                        console.log(res.msg);
                    }
            })
        }
    </script>
@stop

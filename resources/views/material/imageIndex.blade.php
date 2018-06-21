@extends('layouts.index')
@section('title')
    <title>图文素材</title>
@stop
@section('style')
    <style>
        .news_box{
            background-color: #fff;
            width: 200px;
            margin: 10px;
            list-style: none;
            float: left;
        }
        .news_box dl dt{
            padding:12px 15px;
            position: relative;
        }
        .news_box dl dt img{
            width:100%;
            height: 200px;
        }
        .news_box dl dt a{
            position: absolute;
            bottom:12px;
            left:15px;
            width:170px;
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
        .news_box dl dd p{
            margin:0;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        .news_box dl dd .operation{
            position: absolute;
            right: 15px;
            top: 12px;
        }
        .news_box dl dd .operation i{
            margin-left:10px;
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
                    <a>图片消息</a>
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
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">图片</button>
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">语音</button>
                        <button type="button" onclick="location.href='{{route('materialIndex')}}'" class="btn btn-w-m btn-primary">视频</button>
                    </div>
                    <div>
                        <h3>图片消息（共{{$data['total_count']}}条）</h3>
                        <button type="button" id="upload" class="btn btn-w-m btn-info">上传</button>
                        <input type="file" name="image" accept="image/gif,image/jpeg,image/png" multiple style="display: none;">
                    </div>
                    <ul style="padding-left: 0px">
                        @if(!empty($data))
                        @foreach($data['item'] as $k=>$v)
                        <li class="news_box">
                            <dl>
                                <dt>
                                    <img src="{{$v['url']}}" alt="">
                                </dt>
                                <dd>
                                    <p>{{$v['name']}}</p>
                                    <div class="operation">
                                        <i style="cursor: pointer;" class="fa fa-trash" onclick="del('{{$v['media_id']}}')" title="删除"></i>
                                    </div>
                                </dd>
                            </dl>
                        </li>
                        @endforeach
                            @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
        $(function () {
            $('#upload').click(function () {
                $('input[name=image]').click();
            })
            $('input[name=image]').change(function () {

                if($(this)[0].files[0]){
                    var formData = new FormData();
//                    var name = $("input[name=image]").val();
                    var _length = $("input[name=image]")[0].files.length;
                    for(var i=0;i<_length;i++){
                        formData.append("image[]",$("input[name=image]")[0].files[i]);
                    }
//                    formData.append("name",name);
                    swal.enableLoading();
                    $.ajax({
                        headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        url : '{{route('imageAddPost')}}',
                        type : 'POST',
                        data : formData,
                        // 告诉jQuery不要去处理发送的数据
                        processData : false,
                        // 告诉jQuery不要去设置Content-Type请求头
                        contentType : false,
                        beforeSend:function(){
                            console.log("正在进行，请稍候");
                        },
                        success : function(data) {
                            swal.hideLoading();
                            if (data.code == '200') {
                                swal({
                                    title: '上传素材成功',
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
                        error : function(err) {
                            swal.hideLoading();
                            swal('提示','数据异常', 'error');
                        }
                    });
                }
            })
        })
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

@extends('layouts.index')
@section('title')
    <title>公众号设置</title>
@stop
@section('style')
<link href="{{asset('/assets/admin/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>设置</h2>
            <ol class="breadcrumb">
                <li class="active">
                    <a>公众号设置</a>
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
                        账号详情
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content controll_box">
                        <form action="" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">头像：</label>
                                <div class="col-sm-10">
                                    <img style="width:200px;height: 200px;" src="{{$user_info['head_img']}}" alt="">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">二维码：</label>
                                <div class="col-sm-10">
                                    <img src="{{$user_info['qrcode_url']}}" alt="">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">微信号：</label>
                                <div class="col-sm-10">
                                    {{$user_info['alias']}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">APP_ID：</label>
                                <div class="col-sm-10">
                                    {{$user_info['app_id']}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">APP_SCRECT：</label>
                                <div class="col-sm-10">
                                    {{$user_info['app_secret']}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">认证情况：</label>
                                <div class="col-sm-5">
                                    {{$user_info['verify_name']}}
                                </div>
                                <div class="col-sm-5 form-group" id="data_1">
                                    @if($user_info['verify_type']==0)
                                        @if($user_info['auth_expire_in']>0)
                                            过期时间：{{date('Y-m-d',$user_info['auth_expire_in'])}} <a href="javascript:;" onclick="set_expire_in()">设置</a>
                                        @else
                                            过期时间：未设置 <a href="javascript:;" onclick="set_expire_in()">设置</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">主体信息：</label>
                                <div class="col-sm-10">
                                    {{$user_info['principal_name']}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">原始ID：</label>
                                <div class="col-sm-10">
                                    {{$user_info['wx_no']}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">类型：</label>
                                <div class="col-sm-10">
                                    {{$user_info['service_name']}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{asset('/assets/admin/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>
<script>
    $(function () {
        $('#data_1 .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: "yyyy-mm-dd"
        });
    })
    function set_expire_in() {
        var date_str = '过期时间：<div class="input-group date">\n' +
            '                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" id="date_str" class="form-control" value="">\n' +
            '                                            </div>';
        $('#data_1').html(date_str);
        $('#data_1 .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: "yyyy-mm-dd"
        }).on('changeDate',function (e) {
            console.log(e);
            changeDate();
        });
    }
    function changeDate() {
        var date_str=$('#date_str').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url:"{{ route('intoExpireIn') }}",
            type:"post",
            data: {date_str:date_str},
            success: function (data, textStatus, jqXHR) {
                console.log(data);
                var date_html = '过期时间：'+date_str +'<a href="javascript:;" onclick="set_expire_in()">设置</a>';
                $('#data_1').html(date_html);
            }
        })
    }
</script>
@endsection
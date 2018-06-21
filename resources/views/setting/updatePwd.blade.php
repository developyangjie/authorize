@extends('layouts.index')
@section('title')
    <title>修改密码</title>
@stop
@section('style')
@stop
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>修改密码</h2>
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
                        <span>修改密码</span>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal" method="post" id="form1">
                                    <div class="form-group">
                                        <label for="school" class="col-sm-2 control-label">原来密码：</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password" placeholder="请输入密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="school" class="col-sm-2 control-label">新密码：</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="password2" placeholder="请输入新密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="file_text" class="col-sm-2 control-label">确认输入：</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" name="check_pwd" placeholder="确认密码">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="button" class="btn btn-primary" id="submit_btn1" value="提交"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script>
        $('#submit_btn1').click(function () {
            $('#form1').ajaxSubmit({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('updatePwdPost') }}",
                type: 'POST',
                dataType:'json',
                success: function (res) {
                    //console.log(data.code);
                    if(res.code == '200'){
                        swal('提示',res.msg,'success').then(
                            function () {
                                window.location.reload();
                            }
                        );
                    }else{
                        swal('提示',res.msg,'error')
                    }
                },
                error: function (err) {
                    if(err.status == 422){
//                    console.log(JSON.parse(err.responseText));
                        $(JSON.parse(err.responseText).errors).each(function(idx,item){
                            for(var key in item){
                                swal('提示', item[key][0], 'error');
                                return false;
                            }
                            return false;
                        })
                    }
                }
            });
        });
    </script>
@stop

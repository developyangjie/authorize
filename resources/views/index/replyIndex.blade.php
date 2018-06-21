@extends('layouts.index')
@section('title')
    <title>回复首页</title>
@stop
@section('style')
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>回复管理</h2>
            <ol class="breadcrumb">
                <li class="active">
                    <a>回复列表</a>
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
                        <button type="button" id="add" onclick="location.href='{{route('replyAdd')}}'" class="btn btn-w-m btn-primary">添加回复</button>
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
                        <div class="">
                        </div>
                        <table class="table table-striped table-bordered table-hover " id="editable" >
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>关键词</th>
                                <th>匹配类型</th>
                                <th>回复内容</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

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
        $('#editable').dataTable({
            destroy: true,
            processing: true,
            ordering: false,
            searching: false,
            serverSide:true,
            lengthMenu: [20, 50, 100, 200, 1000],
            columns: [
                {"data": "id"},
                {"data": "key_word"},
                {"data": ""},
                {"data": "reply_content"},
                {"data": ""}
            ],
            columnDefs: [
                {
                    targets: 2,
                    render: function (data, type, row) {
                        var match_type = row.match_type;
                        if(match_type==1){
                            return '部分匹配' ;
                        }else{
                            return '完全匹配' ;
                        }
                    }
                },
                {
                    targets: -1,
                    render: function (data, type, row) {
                        var btn1 = '<button type="button" onclick="edit('+ row.id +')" class="btn btn-xs btn-primary">编辑</button>&nbsp;&nbsp;' +
                            '<button type="button" onclick="del('+ row.id +')" class="btn btn-xs btn-danger">删除</button>';
                        return btn1 ;
                    }
                }
            ],
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{route('replyListPost')}}",
                type: 'POST', //GET
                data: function(d){
                },
                //dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                error: function (err, textStatus) {
//                    swal('提示','数据异常', 'error');
                }
            },
            language: {
                url: "{{ URL::asset('assets/admin/js/plugins/dataTables/zh-cn.json') }}"
            }
        });
        //编辑
        function edit(id) {
            location.href='{{route('replyEdit')}}'+'?id='+id;
        }
        //删除
        function del(id) {
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
                        url: "{{route('replyDel')}}",
                        type: 'POST', //GET
                        data: {
                            id: id
                        },
                        //dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                        success: function (data, textStatus, jqXHR) {
                            //console.log(data);alert(data);return;
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

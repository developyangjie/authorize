@extends('layouts.index')
@section('title')
    <title>首页</title>
@stop
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>授权页面</h2>
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
    <div class="middle-box text-center animated fadeInRightBig">
        <h3 class="font-bold">你还没有授权</h3>
        <div class="error-desc"><a target="_blank" href="{{$auth_url}}" class="btn btn-primary m-t">我要授权</a>
        </div>
    </div>
@stop
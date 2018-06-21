@extends('layouts.index')
@section('title')
    <title>首页</title>
@stop
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
    @if(isset($message) && !empty($message))
        <div class="middle-box text-center animated fadeInRightBig">
            <div class="error-desc">
                    {{$message}}
            </div>
        </div>
    @endif
@stop
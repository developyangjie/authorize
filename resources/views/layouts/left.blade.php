<ul class="nav metismenu" id="side-menu">
    <li class="nav-header">
        <div class="dropdown profile-element"> <span>
                            <img alt="头像" style="width: 50px;height: 50px;" class="img-circle" src="{{session('admin_user_info.head_img')}}" />
                             </span>
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"></strong>
                             </span> <span class="text-muted text-xs block">{{session('admin_user_info.org_name')}}<b class="caret"></b></span> </span> </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li><a href="{{route('updatePwd')}}">更改平台密码</a></li>
                <li><a href="{{route('appInfo')}}">公众号信息</a></li>
                <li><a href="{{route('flashUserInfo')}}">刷新公众号信息</a></li>
                <li><a href="{{route('loginOut')}}">退出登录</a></li>
            </ul>
        </div>
        <div class="logo-element">
            365
        </div>
    </li>
    <li>
        <a href="{{route('authIndex')}}"><i class="fa"></i><span class="nav-label">首页</span></a>
    </li>
    <li>
        <a href="javascript:void(0)"><i class="fa"></i> <span class="nav-label">功能</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{route('replyIndex')}}">关键字回复</a></li>
            <li><a href="{{route('getMenuList')}}">自定义菜单</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:void(0)"><i class="fa"></i> <span class="nav-label">管理</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{route('materialIndex')}}">素材管理</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript:void(0)"><i class="fa"></i> <span class="nav-label">设置</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <li><a href="{{route('appInfo')}}">公众号设置</a></li>
        </ul>
    </li>
</ul>
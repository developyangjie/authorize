<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="{{ URL::asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('assets/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin/css/style.css') }}" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">IN+</h1>

            </div>
            <h3>Welcome to IN+</h3>

            <p>Login in. To see it in action.</p>
            <form class="m-t loginForm" role="form" onsubmit="return false">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b login">Login</button>

                {{--<a href="#"><small>Forgot password?</small></a>--}}
                {{--<p class="text-muted text-center"><small>Do not have an account?</small></p>--}}
                {{--<a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>--}}
            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ URL::asset('assets/admin/js/jquery-2.1.1.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/admin/js/plugins/sweetalert/sweetalert2.min.js') }}"></script>

</body>
<script>
    $('.login').click(function(){
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        swal.enableLoading();
        $.ajax({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            url: "{{ route('loginPost') }}",
            data: $('.loginForm').serialize(),
            type: "post",
            success:function(res){
                if(res.code == '200'){
                    location.href = "{{ route('authIndex') }}";
                }else{
                    $(".swal2-container").remove();
                    swal('提示', res.msg, 'error');
                }
            },
            error:function(err){
                $(".swal2-container").remove();
                if(err.status == 422){
                    $(err.responseJSON.errors).each(function(idx,item){
                        for(var key in item){
                            swal('提示', item[key][0], 'error');
                        }
                        return false;
                    })
                }
            }
        })
    })
</script>
</html>

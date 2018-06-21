$(document).ready(function() {
    $.each (city, function (i, item)
    {
        $("#province").append("<option value='"+ i +"'>"+ item.pname +"</option>");
    });
    //省份
    $("#province").change(function() {
        $("#city").children().nextAll().remove();
        $("#region").children().nextAll().remove();
        getSchoolSelect();
        getCity($(this).val(),'city');
    })
    //市
    $("#city").change(function() {
        $("#region").children().nextAll().remove();
        getRegion($("#province").val(),$(this).val(),'region');
        getSchoolSelect();
    })
    $('#region').change(function () {
        getSchoolSelect();
    })
    //选择学校
    $('#school_id').change(function () {
        $("#grade_id").children().nextAll().remove();
        $("#class_id").children().nextAll().remove();
        getGrades();
    })
    //选择年级
    $('#grade_id').change(function () {
        // console.log(1112222);
        getClasses();
    })
function getSchoolSelect(){
    var _token = $('input[name=token]').val();
    var province=$('#province').val();
    var city=$('#city').val();
    var region=$('#region').val();
    $.ajax({
        url: "/admin/user/StudentController",
        type: 'POST',
        data: {
            'province':province,
            'city':city,
            'region':region
        },
        success: function (res) {
//                console.log(res);
            var school_html = '<option value="">学校</option>';
            $(res).each(function (i,item) {
                school_html += '<option value="'+item.id+'">'+item.school_name+'</option>'
            })
            $('#school_id').html(school_html);
        },
        error: function (err, textStatus) {

            swal('提示', '接口异常', 'error');

        }
    })

}
//获取年级
function getGrades() {
    var school_id = $('#school_id').val();
    $.ajax({
        url: "/admin/schoolManage/getGrades",
        type: 'POST',
        data: {id:school_id},
        success: function (data) {
            if (data.code == '200') {
                var str = '<option value="">年级</option>';
                var obj = data.data;
                for(var i in obj){
                    str += "<option value='"+ obj[i].id +"'>"+ obj[i].grade +"</option>";
                }
                $('#grade_id').html(str);
            } else {
                swal('提示', data.msg, 'error');
            }
        },
        error: function (err, textStatus) {

            swal('提示', '接口异常', 'error');

        }
    })
}
//获取班级
function getClasses() {
    var grade_id = $('#grade_id').val();
    var school_id = $('#school_id').val();
    $.ajax({
        url: "/admin/schoolManage/getClassNamesByGrade",
        type: 'POST',
        data: {grade_id:grade_id,school_id:school_id},
        success: function (data) {
            if (data.code == '200') {
                var str = '<option value="">班级</option>';
                var obj = data.data;
                for(var i in obj){
                    str += "<option value='"+ obj[i].id +"'>"+ obj[i].class +"</option>";
                }
                $('#class_id').html(str);
            } else {
                swal('提示', data.msg, 'error');
            }
        },
        error: function (err, textStatus) {

            swal('提示', '接口异常', 'error');

        }
    })
}
});
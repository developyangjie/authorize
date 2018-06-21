//获取城市列表
function getCity(pid,boxid,cityid){
    $.each (city[pid], function (i, item) {
        if(typeof(item) == 'object' ) {
            if(cityid == i){
                $("#" + boxid).append("<option value='" + i + "' selected>" + item.cname + "</option>");
            }else{
                $("#" + boxid).append("<option value='" + i + "'>" + item.cname + "</option>");
            }
        }
    })
}
//获取区县列表
function getRegion(pid,cid,boxid,regionid){
    $.each (city[pid][cid], function (i, item) {
        if(typeof(item) == 'object' ) {
            if(regionid == i){
                $("#" + boxid).append("<option value='" + i + "' selected>" + item.rname + "</option>");
            }else {
                $("#" + boxid).append("<option value='" + i + "'>" + item.rname + "</option>");
            }
        }
    })
}
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="<?php echo CS_PATH;?>pintuer.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CS_PATH;?>fangcms.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CS_PATH;?>bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo CS_PATH;?>bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="fangcms_box">

    <div class="form-group">
        <div>
            <label>
                异常考勤导出:</label>
        </div>
        <div class="form-group">
            <p style="color: darkgray;">开始时间:</p>
            <input class="input" data-date="" data-date-format="dd MM yyyy" size="16" type="text" id="form_dstime" readonly style="margin-left: -1px;"   >
            <p style="color: darkgray;">结束时间:</p>
            <input class="input" size="16" type="text" id="form_detime" readonly style="margin-left: -1px;" >
            <button class="buttont" type="button" id="DepartExt" style="margin-top: 5px">
                导出</button>
        </div>

    </div>
</div>
</body>
</html>
<script src="<?php echo JS_PATH;?>jquery.min.js" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>pintuer.js" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>/lib/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>datetimepicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>datetimepicker/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>datetimepicker/bootstrap-datetimepicker.zh-CN.js"></script>

<script type="text/javascript">
    //部门
    $('#form_dstime').datetimepicker({
        format: 'yyyy-mm-dd',
        language:  'zh',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('#form_detime').datetimepicker({
        format: 'yyyy-mm-dd',
        language:  'zh',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('#DepartExt').on('click',function(){
        var st = $('#form_dstime');
        var et = $('#form_detime');
        if(st.val()==''){
            layer.tips('请选择', st);
            st.focus();
            return false
        }
        if(et.val()==''){
            layer.tips('请选择',et);
            et.focus();
            return false;
        }
        if(et.val() < st.val()){
            parent.layer.msg('结束时间不能小于开始时间,请重新选择', {
                offset: 200,
                shift: 1,
                time: 1200, //2秒关闭（如果不配置，默认是3秒）
            });
            return false;
        }
        var dstime = st.val();
        var detime = et.val();
        window.location.href ="/Admin/Work/DepartExt/dst/"+dstime+'/det/'+detime;
    })

</script>
<?php
/**
 * Created by PhpStorm.
 * User: 呆呆熊
 * Date: 2018/1/22
 * Time: 0:05
 */
class requestResponse {
    public $Status = "";
    public $StatusCode = "";
    public $Description="";
    public $Error = "";
    public $Ret_Data="";
}
$retResult = new requestResponse();//一个返回对象

session_start();
if (!(isset($_SESSION["admin_id"]) && !empty($_SESSION["admin_id"]) &&
    isset($_SESSION["admin_name"]) && !empty($_SESSION["admin_name"]))
)//登陆判断如果没有登陆，是否需要跳转***oauth_log.php
{
    $retResult->Status = "failed";
    $retResult->StatusCode = 0;
    $retResult->Description = "";
    $retResult->Error = "管理员未登录";
    $retResult->Ret_Data = "";
    exit(json_encode($retResult));//失败返回相关信息
}
if(isset($_GET['paper_id']) && preg_match('/[0-9]{1,}/',$_GET['paper_id']))
{
    include_once 'json_admin.php';
    $paper_id = $_GET['paper_id'];
    //利用函数禁用问卷（改变为相反的值）
    $sql="select * from tbl_quepublish WHERE  publish_id='".$paper_id."'";
    if($result=mysqli_query($dbcon,$sql))
    {
        $row = $result->fetch_assoc();
        $able = $row['is_enable'];
        mysqli_free_result($result);
        if($able==0)
        {
            $sql="UPDATE tbl_quepublish SET is_enable = 1 WHERE publish_id = '".$paper_id."'";
            if(!mysqli_query($dbcon,$sql))
            {
                $retResult->Status= "failed";
                $retResult->StatusCode = 0;
                $retResult->Description="";
                $retResult->Error="数据库操作is_enable失败";
                $retResult->Ret_Data="";
                $dbcon->close();
                exit(json_encode($retResult));//失败返回相关信息

            }
            $retResult->Status= "success";
            $retResult->StatusCode = 1;
            $retResult->Description="";
            $retResult->Error="";
            $retResult->Ret_Data="";
            $dbcon->close();
            exit(json_encode($retResult));

        }
        else if($able==1)
        {
            $sql="UPDATE tbl_quepublish SET is_enable = 0 WHERE publish_id = '".$paper_id."'";
            mysqli_query($dbcon,$sql);
            if(!mysqli_query($dbcon,$sql))
            {
                $retResult->Status= "failed";
                $retResult->StatusCode = 0;
                $retResult->Description="";
                $retResult->Error="数据库操作is_enable失败";
                $retResult->Ret_Data="";
                $dbcon->close();
                exit(json_encode($retResult));//失败返回相关信息

            }
            $retResult->Status= "success";
            $retResult->StatusCode = 1;
            $retResult->Description="";
            $retResult->Error="";
            $retResult->Ret_Data="";
            $dbcon->close();
            exit(json_encode($retResult));

        }

    }
    else{
        $retResult->Status= "failed";
        $retResult->StatusCode = 0;
        $retResult->Description="";
        $retResult->Error="数据库查询问卷id失败";
        $retResult->Ret_Data="";
        $dbcon->close();
        exit(json_encode($retResult));//失败返回相关信息
    }
}
else{
    $retResult->Status= "failed";
    $retResult->StatusCode = 0;
    $retResult->Description="";
    $retResult->Error="get接收到的参数为空";
    $retResult->Ret_Data="";
    $dbcon->close();
    exit(json_encode($retResult));//失败返回相关信息
}

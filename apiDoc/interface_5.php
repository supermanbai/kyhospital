<?PHP
/*
5、预约挂号 这个是哪个存储过程？
Mr.Zhong:
2、3、4这三个存储，第一个获取挂号科室信息，第二个获取具体科室下医生或者号种信息，第三个缴费后把挂号信息及交易信息回传给his

韩述:
[图片]

韩述:
这个 ampm 字段，我直接传上午、下午、全天和夜间就可以了吧？

Mr.Zhong:
对，你获取科室下挂号信息的会有这些字段的值

Mr.Zhong:
你获取到什么值，传什么值就行了

*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

//-2------
$sql = 'exec dbo.PRO_YW_GH_PB;';
$pdo->prepare($sql);
$res = $pdo->fetchAll();


if(count($res) == 0 ||
   count($res[0]) == 0
){
	   print "获取不到科室信息.\n";
	   exit();
}

$ksmc = trim($res[0][8]['KSMC']);
$ksdm = $res[0][8]['CZKS'];
printf("%sの科室代码代码：%d\n",$ksmc, $ksdm);


//-3------
/*   @RQ DATETIME, --挂号日期 '2015-09-01 00:00:00'
    @KSDM CHAR(4) 
AS   
/*    
-------------------------------------------    
--方法说明：日期和科室出诊医生和号类
-------------------------------------------    
--参数说明：   
    @RQ DATETIME, --挂号日期 '2015-09-01 00:00:00'
 @KSDM CHAR(4), --出诊科室

 返回
 LX  类型 0普通号 1 专家号  CZKS 出诊科室，YSDM出诊医师，YSMC 医生名称,ZCMC 职称名称 LXDM挂号类型，LXMC 类型名称,AMPM安排，SYHY 剩余号源 GHJE 挂号金额
 ------------------------------------------    
*/
$sql = 'exec dbo.PRO_YW_GH_PB_KS @RQ = :RQ,@KSDM = :KSDM;';
$pdo->prepare($sql);

$pdo->bindParam(':RQ','2020-08-23 00:00:00', PDO::PARAM_STR);
$pdo->bindParam(':KSDM',$ksdm, PDO::PARAM_STR);

$res = $pdo->fetchAll();

if(count($res) == 0 ||
   count($res[0]) == 0
   ){
	   print "科室暂无号源.\n";
	   exit();	   
   }

$czks = $res[0][1]['CZKS'];
$ampm = $res[0][1]['AMPM'];
$syhy = $res[0][1]['SYHY'];
$ghje = floatval($res[0][1]['GHJE']);
printf("%sの科室代码代码：%d，czks: %d\tampm:%s\t剩余号源：%s\t挂号金额：%f\n",$ksmc, $ksdm, $czks, $ampm,$syhy, $ghje);
//-PRO_YW_MZGH---------------

/*@jzkh  VARCHAR(20),
  @ssje  DECIMAL(10,2),
  @jzrq  DATETIME,
  @ampm  VARCHAR(4),  
  @ghlx  VARCHAR(4), --001 普通医师 002 副主任  003 主任 003 急诊  005 免费  005 优惠号 007 知名专家  010 简易号 参考 mzgh_code_ghlxdmb
  @ghlb  VARCHAR(4),-- 01 出诊  02 复诊  --03接诊 -14 不诊断  参考 mzgh_code_ghlbdmb
  @ghks  VARCHAR(4),    
  @ghys  VARCHAR(4), 
  @wxddh  VARCHAR(100),
  @wxopenid VARCHAR(100),
  @zffs  VARCHAR(10),
  @czydm  VARCHAR(20)
/*
-------------------------------------------
--方法说明：挂号-
-------------------------------------------
--参数说明： 
 @jzkh  付费卡号 一卡通号
 @ssje  实收金额
 @jzrq  挂号时间 
 @ampm  挂号的时段--全天,上午,下午,夜间 参考 表 MZGH_CODE_AMSD 
 @ghlx   --001 普通医师 002 副主任  003 主任 003 急诊  005 免费  005 优惠号 007 知名专家  010 简易号 参考 mzgh_code_ghlxdmb
 @ghlb   -- 01 出诊  02 复诊  --03接诊 -14 不诊断  参考 mzgh_code_ghlbdmb
 @ghks  挂号科室 --科室代码  参考 code_ksdm
 @ghys  挂号医师 --医师代码 参考 code_ysdm  目前无专家号 可以不用
 @wxddh   
 @wxopenid  
 @zffs  
 @czydm  
-------------------------------------------*/
$sql = 'exec dbo.PRO_YW_MZGH @jzkh=:jzkh,@ssje=:ssje,@jzrq=:jzrq,@ampm=:ampm,@ghlx=:ghlx,@ghlb=:ghlb,@ghks=:ghks,@ghys=:ghys,@wxddh=:wxddh,@wxopenid=:wxopenid,@zffs=:zffs,@czydm=:czydm;';
$pdo->prepare($sql);

$pdo->bindParam(':jzkh','123', PDO::PARAM_STR);
$pdo->bindParam(':ssje',$ghje);
$pdo->bindParam(':jzrq','2020-08-23 00:00:00', PDO::PARAM_STR);
$pdo->bindParam(':ampm',$ampm, PDO::PARAM_STR);
$pdo->bindParam(':ghlx','04', PDO::PARAM_STR);
$pdo->bindParam(':ghlb','01', PDO::PARAM_STR);
$pdo->bindParam(':ghks',$czks, PDO::PARAM_STR);
$pdo->bindParam(':ghys','', PDO::PARAM_STR);
$pdo->bindParam(':wxddh','wxopenid3');
$pdo->bindParam(':wxopenid','wxopenid', PDO::PARAM_STR);
$pdo->bindParam(':zffs','89', PDO::PARAM_STR);
$pdo->bindParam(':czydm','king', PDO::PARAM_STR);

$pdo->execute();
print "挂号成功\n";
/*
Active code page: 65001
have 19 recodes.
内一科病区の科室代码代码：6
have 3 recodes.
Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [LX] => 0
                    [0] => 0
                    [CZKS] => 0006
                    [1] => 0006
                    [YSDM] =>
                    [2] =>X
                    [YSMC] =>
                    [3] =>
                    [ZCMC] =>
                    [4] =>
                    [LXDM] => 01
                    [5] => 01
                    [LXMC] => 免费号
                    [6] => 免费号
                    [AMPM] => 全天
                    [7] => 全天
                    [SYHY] => 4994
                    [8] => 4994
                    [GHJE] => .00
                    [9] => .00
                )

            [1] => Array
                (
                    [LX] => 0
                    [0] => 0
                    [CZKS] => 0006
                    [1] => 0006
                    [YSDM] =>
                    [2] =>
                    [YSMC] =>
                    [3] =>
                    [ZCMC] =>
                    [4] =>
                    [LXDM] => 04
                    [5] => 04
                    [LXMC] => 普通号
                    [6] => 普通号
                    [AMPM] => 全天
                    [7] => 全天
                    [SYHY] => 5000
                    [8] => 5000
                    [GHJE] => 3.00
                    [9] => 3.00
                )

            [2] => Array
                (
                    [LX] => 0
                    [0] => 0
                    [CZKS] => 0006
                    [1] => 0006
                    [YSDM] =>
                    [2] =>
                    [YSMC] =>
                    [3] =>
                    [ZCMC] =>
                    [4] =>
                    [LXDM] => 14
                    [5] => 14
                    [LXMC] => 毒麻方
                    [6] => 毒麻方
                    [AMPM] => 全天
                    [7] => 全天
                    [SYHY] => 5000
                    [8] => 5000
                    [GHJE] => 6.00
                    [9] => 6.00
                )

        )

)
请按任意键继续. . .

Active code page: 65001
have 19 recodes.
Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [CZKS] => 0006
                    [0] => 0006
                    [KSMC] => 内一科病区
                    [1] => 内一科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [1] => Array
                (
                    [CZKS] => 0006
                    [0] => 0006
                    [KSMC] => 内一科病区
                    [1] => 内一科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 14
                    [3] => 14
                    [lxmc] => 毒麻方
                    [4] => 毒麻方
                    [JE] => 6.00
                    [5] => 6.00
                )

            [2] => Array
                (
                    [CZKS] => 0007
                    [0] => 0007
                    [KSMC] => 外科病区
                    [1] => 外科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [3] => Array
                (
                    [CZKS] => 0007
                    [0] => 0007
                    [KSMC] => 外科病区
                    [1] => 外科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 14
                    [3] => 14
                    [lxmc] => 毒麻方
                    [4] => 毒麻方
                    [JE] => 6.00
                    [5] => 6.00
                )

            [4] => Array
                (
                    [CZKS] => 0008
                    [0] => 0008
                    [KSMC] => 血液透析中心
                    [1] => 血液透析中心
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [5] => Array
                (
                    [CZKS] => 0009
                    [0] => 0009
                    [KSMC] => 手术部
                    [1] => 手术部
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [6] => Array
                (
                    [CZKS] => 0093
                    [0] => 0093
                    [KSMC] => 消化内科
                    [1] => 消化内科
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [7] => Array
                (
                    [CZKS] => 0130
                    [0] => 0130
                    [KSMC] => 外科专家门诊孟庆海
                    [1] => 外科专家门诊孟庆海
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [8] => Array
                (
                    [CZKS] => 1218
                    [0] => 1218
                    [KSMC] => 妇产科病区
                    [1] => 妇产科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [9] => Array
                (
                    [CZKS] => 1228
                    [0] => 1228
                    [KSMC] => 内二科病区
                    [1] => 内二科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [10] => Array
                (
                    [CZKS] => 1321
                    [0] => 1321
                    [KSMC] => 门诊手术室
                    [1] => 门诊手术室
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [11] => Array
                (
                    [CZKS] => 1346
                    [0] => 1346
                    [KSMC] => 内三科病区
                    [1] => 内三科病区
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [12] => Array
                (
                    [CZKS] => 1353
                    [0] => 1353
                    [KSMC] => 专家诊室2黄海鑫
                    [1] => 专家诊室2黄海鑫
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [13] => Array
                (
                    [CZKS] => 1359
                    [0] => 1359
                    [KSMC] => 内科专家门诊
                    [1] => 内科专家门诊
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [14] => Array
                (
                    [CZKS] => 1361
                    [0] => 1361
                    [KSMC] => 康复医学科
                    [1] => 康复医学科
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [15] => Array
                (
                    [CZKS] => 1362
                    [0] => 1362
                    [KSMC] => 妇科门诊2郑吉平
                    [1] => 妇科门诊2郑吉平
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [16] => Array
                (
                    [CZKS] => 1363
                    [0] => 1363
                    [KSMC] => 中医门诊3汪文强
                    [1] => 中医门诊3汪文强
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [17] => Array
                (
                    [CZKS] => 1364
                    [0] => 1364
                    [KSMC] => 内科门诊4董瑞兰
                    [1] => 内科门诊4董瑞兰
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

            [18] => Array
                (
                    [CZKS] => 1365
                    [0] => 1365
                    [KSMC] => 内科门诊5王学云
                    [1] => 内科门诊5王学云
                    [SXH] => 1
                    [2] => 1
                    [lxdm] => 04
                    [3] => 04
                    [lxmc] => 普通号
                    [4] => 普通号
                    [JE] => 3.00
                    [5] => 3.00
                )

        )

)
rechage ok
请按任意键继续. . .
*/
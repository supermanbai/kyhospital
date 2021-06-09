<?PHP
/*
7、住院清单查看 这个是存储过程【15.住院每日费用明细】吧？

韩述:
【16.住院每日费用汇总】这个就是一个表头，可调可不调

Mr.Zhong:
对，但是你不调用，怎么知道患者哪天有费用

*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

$blh = '202103660';
$jzkh = '123';
//【16.住院每日费用汇总】
$sql = 'exec dbo.PRO_ZY_CX_FYQDList @blh=:blh,@jzkh=:jzkh;';
$pdo->prepare($sql);

$pdo->bindParam(':blh', $blh, PDO::PARAM_STR);
$pdo->bindParam(':jzkh',$jzkh, PDO::PARAM_STR);

$res = $pdo->fetchAll();
if(count($res) == 0 ||
   count($res[0]) == 0 
   ){
	   print("没有查到收费\n");
	   exit();
   }

$rq = $res[0][0]['rq'];
foreach($res[0] as $val){
		printf("%s 收费 :￥%f\n",trim($val['rq']), $val['zje']);
}
print "---------------------\n\n\n";
//-【15.住院每日费用明细】------
$sql = 'exec dbo.PRO_ZY_CX_FYQD @blh=:blh,@rq=:rq;';
$pdo->prepare($sql);

$pdo->bindParam(':blh', $blh, PDO::PARAM_STR);
$pdo->bindParam(':rq',$rq.' 00:00:00', PDO::PARAM_STR);

$res = $pdo->fetchAll();
foreach($res[0] as $key=>$val){
	printf("%d) %s【%s】%s / ￥%f x %d = ￥%f\n", $key, substr($val['sfsj'],0,19),trim($val['fylx']),trim($val['mc']),$val['dj'],$val['sl'],$val['je']);
}
exit();

//202103660
/*
/*  
-------------------------------------------  
--方法说明：住院--查询--费用清单
-------------------------------------------  
--参数说明：  
 @blh：    病历号或就诊卡号varchar
 @rq :    日期datetime
-------------------------------------------  
--结果集说明：
字段 数据类型  长度 必填 说明
zyh  varchar   10  Y  住院号(主键) 内部使用，区分唯一一次住院
blh  varchar   20  Y  病历号(主键)
zycs int      Y  住院次数(主键)
jzkh varchar   32  N  卡号
hzxm varchar   50  Y  姓名
nl  int      Y  年龄
nldw varchar   10  N  年龄单位（1:岁2:月3:周4:天5:小时）
sfzh varchar   20  N  身份证号
ysdm Varchar   4  Y  住院医生编码
ysmc varchar   50  Y  住院医生名称
ksdm Varchar   4  Y  住院科室编码
ksmc varchar   50  Y  住院科室
rysj datetime    Y  入院时间
cwh  varchar   20  N  床位号
fyhj Decimal   18,2 Y  总费用
yj  Decimal   18,2 Y  预交金额
ye  Decimal   18,2 Y  余额
ryzd Varchar   100  N  入院诊断
cyzd Varchar   100  N  出院主要诊断
cysj datetime    Y  出院时间
ssmc Varchar   100  N  主要手术
sssj datetime    N  手术时间

------------------------------------------  
Active code page: 65001
have 2 recodes.
Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [blh] => 202103660
                    [0] => 202103660
                    [dh] => A000321176
                    [1] => A000321176
                    [dm] => 100155
                    [2] => 100155
                    [mc] => 654-2片                                                                                     
                    [3] => 654-2片                                                                                      
                    [sfsj] => 2021-06-07 17:30:42.103
                    [4] => 2021-06-07 17:30:42.103
                    [fylx] => 西药
                    [5] => 西药
                    [dj] => .1863
                    [6] => .1863
                    [sl] => 1
                    [7] => 1
                    [je] => .19
                    [8] => .19
                    [sxh] => 1
                    [9] => 1
                    [ksysdm] => 0057
                    [10] => 0057
                    [kjysmc] => 内二科病区
                    [11] => 贺英
                    [kjysdm] => 1228
                    [12] => 1228
                    [13] => 内二科病区
                    [zxksdm] => 1101
                    [14] => 1101
                    [zxksmc] => 西药房
                    [15] => 西药房
                    [fl] => WQY
                    [16] => WQY
                )

            [1] => Array
                (
                    [blh] => 202103660
                    [0] => 202103660
                    [dh] => A000321177
                    [1] => A000321177
                    [dm] => 25010100801
                    [2] => 25010100801
                    [mc] => 红细胞沉降率测定(ESR)手工法                                                                 
                    [3] => 红细胞沉降率测定(ESR)手工法                                                                  
                    [sfsj] => 2021-06-07 17:31:06.637
                    [4] => 2021-06-07 17:31:06.637
                    [fylx] => 化验
                    [5] => 化验
                    [dj] => 2.0000
                    [6] => 2.0000
                    [sl] => 1
                    [7] => 1
                    [je] => 2.00
                    [8] => 2.00
                    [sxh] => 1
                    [9] => 1
                    [ksysdm] => 0057
                    [10] => 0057
                    [kjysmc] => 内二科病区
                    [11] => 贺英
                    [kjysdm] => 1228
                    [12] => 1228
                    [13] => 内二科病区
                    [zxksdm] => 0024
                    [14] => 0024
                    [zxksmc] => 检验中心
                    [15] => 检验中心
                    [fl] => JCDSF
                    [16] => JCDSF
                )

        )

)
请按任意键继续. . .

*/ 
<?PHP
/*
6、门诊清单查看 这个功能也是【11.就诊记录】和【12.门诊费用清单】一起调用吗？
韩述:
就诊的时候，医生有个签字的化验单和处方，电子版的也可以获取到吗？

Mr.Zhong:
对，通过就诊记录获取jzid，作为费用清单的入参

Mr.Zhong:
电子版获取不到

*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

//-------
$jzkh = '123';

$sql = 'exec dbo.PRO_MZ_CX_JZJL @jzkh=:jzkh,@jzsjqs=:jzsjqs,@jzsjzs=:jzsjzs;';
$pdo->prepare($sql);

$pdo->bindParam(':jzkh', $jzkh, PDO::PARAM_STR);
$pdo->bindParam(':jzsjqs','2020-01-01 00:00:00', PDO::PARAM_STR);
$pdo->bindParam(':jzsjzs','2022-01-01 00:00:00', PDO::PARAM_STR);
$res = $pdo->fetchAll();

if(count($res) == 0 ||count($res[0]) == 0){
	print "没有门诊检查清单\n";
	exit();
}
print_r($res);
$jzid = $res[0][0]['jzid'];
$jzsj = $res[0][0]['jzsj'];
printf("就诊 id:%s\n",$jzid);
/*  PRO_MZ_CX_FYQD
-------------------------------------------  
--方法说明：门诊--查询--费用清单
-------------------------------------------  
--参数说明：  
 @jzid：    就诊ID
-------------------------------------------  
--结果集说明：
字段 数据类型  长度 必填 说明
jzid Varchar   50  Y  就诊ID（索引）
dh  Varchar   30  Y  单据号（主键）
tcdm Varchar   50  Y  套餐代码（主键）只为插数据用不显示
dm  Varchar   50  Y  费用代码（主键）
mc  Varchar   100  Y  费用名称
dj  decimal   18,4 Y  单价
sl  Int      Y  数量
je  decimal   18,2 Y  金额
fl  varchar   20  Y  分类（主键 WQY:未取药处方 
               YQY：已取药处方 
               WQYTF：未取药退费 
               YQYTF：已取药退费 
               JCDSF：检查单收费 
               JCDTF：检查单退费）
------------------------------------------  
*/   
$sql = 'exec dbo.PRO_MZ_CX_FYQD @jzid=:jzid;';
$pdo->prepare($sql);

$pdo->bindParam(':jzid', $jzid, PDO::PARAM_STR);
$res = $pdo->fetchAll();

foreach($res[0] as $key=>$val)
{
	printf("%d) %s【%s】 %s(%d) ￥%f x %d = ￥%f\n", $key,trim($jzsj),trim($val['fl']), trim($val['mc']),trim($val['dm']),trim($val['dj']),trim($val['sl']),trim($val['ssje']));
}
/*
Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [brid] => 2000114132
                    [1] => 2000114132
                    [jzkh] => 123
                    [2] => 123
                    [jzsj] => 2021-06-07
                    [3] => 2021-06-07
                    [zd] =>
                    [4] =>
                    [ksdm] => 0011
                    [5] => 0011
                    [ksmc] => 中医门诊1肖树礼
                    [6] => 中医门诊1肖树礼
                    [ysdm] => 0087
                    [7] => 0087
                    [ysmc] => 肖树礼
                    [8] => 肖树礼
                )
        )
)

//----------------------
Active code page: 65001
have 1 recodes.
就诊 id:2106072
have 18 recodes.
Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711086
                    [1] => A808711086
                    [tcdm] =>
                    [2] =>
                    [dm] => 100004
                    [3] => 100004
                    [mc] => 开塞露                                                                                      
                    [4] => 开塞露                                                                                       
                    [dj] => 1.0005
                    [5] => 1.0005
                    [sl] => 1
                    [6] => 1
                    [ssje] => 1.00
                    [7] => 1.00
                    [fl] => WQY
                    [8] => WQY
                )

            [1] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711086
                    [1] => A808711086
                    [tcdm] =>
                    [2] =>
                    [dm] => 100014
                    [3] => 100014
                    [mc] => 北豆根胶囊                                                                                  
                    [4] => 北豆根胶囊                                                                                   
                    [dj] => 7.7050
                    [5] => 7.7050
                    [sl] => 1
                    [6] => 1
                    [ssje] => 7.71
                    [7] => 7.71
                    [fl] => WQY
                    [8] => WQY
                )

            [2] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711087
                    [1] => A808711087
                    [tcdm] =>
                    [2] =>
                    [dm] => 300002
                    [3] => 300002
                    [mc] => 八角茴香                                                                                    
                    [4] => 八角茴香                                                                                     
                    [dj] => .0400
                    [5] => .0400
                    [sl] => 3
                    [6] => 3
                    [ssje] => .12
                    [7] => .12
                    [fl] => WQY
                    [8] => WQY
                )

            [3] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711088
                    [1] => A808711088
                    [tcdm] => ct2000007
                    [2] => ct2000007
                    [dm] => 21030000001
                    [3] => 21030000001
                    [mc] => X线计算机体层(CT)扫描(使用螺旋扫描加收)
                    [4] => X线计算机体层(CT)扫描(使用螺旋扫描加收)
                    [dj] => 60.0000
                    [5] => 60.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 60.00
                    [7] => 60.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [4] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711088
                    [1] => A808711088
                    [tcdm] => ct2000007
                    [2] => ct2000007
                    [dm] => 21030000100
                    [3] => 21030000100
                    [mc] => X线计算机体层(CT)平扫
                    [4] => X线计算机体层(CT)平扫
                    [dj] => 120.0000
                    [5] => 120.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 120.00
                    [7] => 120.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [5] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711089
                    [1] => A808711089
                    [tcdm] => ct20000006
                    [2] => ct20000006
                    [dm] => 21030000001
                    [3] => 21030000001
                    [mc] => X线计算机体层(CT)扫描(使用螺旋扫描加收)
                    [4] => X线计算机体层(CT)扫描(使用螺旋扫描加收)
                    [dj] => 60.0000
                    [5] => 60.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 60.00
                    [7] => 60.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [6] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711089
                    [1] => A808711089
                    [tcdm] => ct20000006
                    [2] => ct20000006
                    [dm] => 21030000100
                    [3] => 21030000100
                    [mc] => X线计算机体层(CT)平扫
                    [4] => X线计算机体层(CT)平扫
                    [dj] => 120.0000
                    [5] => 120.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 120.00
                    [7] => 120.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [7] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 2503050080300
                    [3] => 2503050080300
                    [mc] => 血清天门冬氨酸氨基转移酶测定速率法..
                    [4] => 血清天门冬氨酸氨基转移酶测定速率法..
                    [dj] => 4.0000
                    [5] => 4.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 4.00
                    [7] => 4.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [8] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 25030600102
                    [3] => 25030600102
                    [mc] => 血清肌酸激酶测定速率法                                                                      
                    [4] => 血清肌酸激酶测定速率法                                                                       
                    [dj] => 4.0000
                    [5] => 4.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 4.00
                    [7] => 4.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [9] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 25030600203
                    [3] => 25030600203
                    [mc] => 血清肌酸激酶-MB同工酶测定速率法
                    [4] => 血清肌酸激酶-MB同工酶测定速率法
                    [dj] => 8.0000
                    [5] => 8.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 8.00
                    [7] => 8.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [10] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 25030600502
                    [3] => 25030600502
                    [mc] => 乳酸脱氢酶测定速率法                                                                        
                    [4] => 乳酸脱氢酶测定速率法                                                                         
                    [dj] => 5.0000
                    [5] => 5.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 5.00
                    [7] => 5.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [11] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 250306007
                    [3] => 250306007
                    [mc] => 羟丁酸脱氢酶速率法
                    [4] => 羟丁酸脱氢酶速率法
                    [dj] => 4.0000
                    [5] => 4.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 4.00
                    [7] => 4.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [12] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 25030600902
                    [3] => 25030600902
                    [mc] => 血清肌钙蛋白I测定各种免疫学法.
                    [4] => 血清肌钙蛋白I测定各种免疫学法.
                    [dj] => 40.0000
                    [5] => 40.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 40.00
                    [7] => 40.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [13] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711090
                    [1] => A808711090
                    [tcdm] => m00000070
                    [2] => m00000070
                    [dm] => 25030601001
                    [3] => 25030601001
                    [mc] => 血清肌红蛋白各种免疫学法
                    [4] => 血清肌红蛋白各种免疫学法
                    [dj] => 16.0000
                    [5] => 16.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 16.00
                    [7] => 16.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [14] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711091
                    [1] => A808711091
                    [tcdm] => hy200048
                    [2] => hy200048
                    [dm] => 25030700103
                    [3] => 25030700103
                    [mc] => 尿素测定酶促动力学法
                    [4] => 尿素测定酶促动力学法
                    [dj] => 5.0000
                    [5] => 5.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 5.00
                    [7] => 5.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [15] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711091
                    [1] => A808711091
                    [tcdm] => hy200048
                    [2] => hy200048
                    [dm] => 25030700202
                    [3] => 25030700202
                    [mc] => 肌酐测定酶促动力学法                                                                        
                    [4] => 肌酐测定酶促动力学法                                                                         
                    [dj] => 5.0000
                    [5] => 5.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 5.00
                    [7] => 5.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [16] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711091
                    [1] => A808711091
                    [tcdm] => hy200048
                    [2] => hy200048
                    [dm] => 25030700500
                    [3] => 25030700500
                    [mc] => 血清尿酸测定                                                                                
                    [4] => 血清尿酸测定                                                                                 
                    [dj] => 4.0000
                    [5] => 4.0000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 4.00
                    [7] => 4.00
                    [fl] => JCDSF
                    [8] => JCDSF
                )

            [17] => Array
                (
                    [jzid] => 2106072
                    [0] => 2106072
                    [dh] => A808711092
                    [1] => A808711092
                    [tcdm] => 000010011
                    [2] => 000010011
                    [dm] => 000010011
                    [3] => 000010011
                    [mc] => 医用夹板12*76*0.75cm
                    [4] => 医用夹板12*76*0.75cm
                    [dj] => 459.9000
                    [5] => 459.9000
                    [sl] => 1
                    [6] => 1
                    [ssje] => 459.90
                    [7] => 459.90
                    [fl] => JCDSF
                    [8] => JCDSF
                )

        )

)
请按任意键继续. . .
*/
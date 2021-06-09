<?PHP
/*
1、医卡通绑定：7、建档(建档成功之后拿到brid，然后再办卡，jzkh 入参用你们生成的卡号。nldw 传1，民族传汉字就行了)->8、办卡->9、就诊卡绑定
*/

//7、建档(建档成功之后拿到brid，然后再办卡
include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

$jzkh = 'manual-1622798276';//.time();
$sql = 'exec dbo.PRO_EPMI_JD @jzkh = :jzkh,@xm = :xm,@xb = :xb,@nl = :nl,@nldw = :nldw,@mz = :mz, @sfrq = :sfrq , @sfzh = :sfzh, @jtdz = :jtdz, @sf = :sf,@sdq = :sdq,@czydm = :czydm,@jtdh = :jtdh;';
$pdo->prepare($sql);

$pdo->bindParam(':jzkh',$jzkh, PDO::PARAM_STR);
$pdo->bindParam(':xm',"柿红侠", PDO::PARAM_STR);
$pdo->bindParam(':xb',"性别", PDO::PARAM_STR);
$pdo->bindParam(':nl',333, PDO::PARAM_INT);
$pdo->bindParam(':nldw',"1", PDO::PARAM_STR);
$pdo->bindParam(':mz',"汉", PDO::PARAM_STR);
$pdo->bindParam(':sfrq',"1983-06-27 12:00:00", PDO::PARAM_STR);
$pdo->bindParam(':sfzh',"110103198306270617", PDO::PARAM_STR);
$pdo->bindParam(':jtdz',"北京市东城区龙潭街道光明楼1号楼3单元502", PDO::PARAM_STR);
$pdo->bindParam(':sf',"北京", PDO::PARAM_STR);
$pdo->bindParam(':sdq',"崇文", PDO::PARAM_STR);
$pdo->bindParam(':jtdh',"13366073807", PDO::PARAM_STR);
$pdo->bindParam(':czydm',"h5", PDO::PARAM_STR);

/*
-------------------------------------------
--方法说明：建档
-------------------------------------------
--参数说明： 
 @jzkh  付费卡号 一卡通号
 @xm   患者姓名 
 @xb      性别 1 男 2 女 9 未知
 @nl      年龄
 @nldw  年龄单位
 @mz      民族 his.dbo.code_mz 
 @sfrq       出生日期
 @sfzh       身份证号
 @jtdz       家庭地址
 @sf         省份
 @sdq        区县
 @czydm      建档机器编号
--@xm -   @sdq 都是从 身份证读卡器自动获得 
-------------------------------------------
*/
$res = $pdo->fetchAll();

if(!isset($res[0][0]['brid'])){
	print_r($res);
	print "建档失败\n";
	exit();
}

$brid = $res[0][0]['brid'];
printf("The brid is :%d\n", $brid);


//8、办卡
$sql = 'exec dbo.PRO_YKT_JK @czydm = :czydm,@jzkh = :jzkh,@klx = :klx,@brid = :brid,@sfjm = :sfjm,@kmm = :kmm,@kply = :kply,@kbh = :kbh,  @zffs = :zffs, @wxddh = :wxddh;';
$pdo->prepare($sql);

$pdo->bindParam(':czydm',"h5", PDO::PARAM_STR);
$pdo->bindParam(':jzkh',$jzkh, PDO::PARAM_STR);
$pdo->bindParam(':klx',"4", PDO::PARAM_STR);
$pdo->bindParam(':brid',$brid, PDO::PARAM_STR);
$pdo->bindParam(':sfjm',null, PDO::PARAM_NULL);
$pdo->bindParam(':kmm',null, PDO::PARAM_NULL);
$pdo->bindParam(':kply',"0", PDO::PARAM_STR);
$pdo->bindParam(':kbh',null, PDO::PARAM_NULL);
$pdo->bindParam(':zffs',"1", PDO::PARAM_STR);
$pdo->bindParam(':wxddh',"微信充值订单号", PDO::PARAM_STR);

$pdo->execute();
print "step 8.finish\n";
/*
PRO_YKT_JK

  @czydm  VARCHAR(4),
  @jzkh  VARCHAR(50),
  @klx   VARCHAR(10),
  @brid  VARCHAR(20),
  @sfjm  VARCHAR(1), 
  @kmm       VARCHAR(256),
  @kply  VARCHAR(1),    
  @kbh       VARCHAR(50),
  @zffs  VARCHAR(4),
  @wxddh      VARCHAR(100) = NULL
  
-------------------------------------------
--方法说明：建卡
-------------------------------------------
--参数说明： 
 @czydm  建卡机器代码
 @jzkh  付费卡号 一卡通号
 @klx   卡类型  --his. k_klx 表 默认4
 @brid  病人ID 号 
 @sfjm  是否加密 0 不加 1 加密  可为null
 @kmm   密码 可为null
 @kply  卡片来源 可为null
 @kbh   卡片内部编号 可以null 针对健康卡 需要输入卡内部编号
 @zffs  卡费支付方式  
 @wxddh  微信订单号
-------------------------------------------
*/  



//9、就诊卡绑定
/*  
PRO_MZ_YW_HZPD
-------------------------------------------  
--方法说明：
-------------------------------------------  
--参数说明：  
 @JZKH    就诊卡号
 @XM                姓名
-------------------------------------------  
--结果集说明：
字段 数据类型  长度 必填 说明
fhjg int       返回结果  1 存在 0 不存在
------------------------------------------  
*/   
$sql = 'exec dbo.PRO_MZ_YW_HZPD @JZKH = :JZKH,@XM = :XM';
$pdo->prepare($sql);

$pdo->bindParam(':JZKH',$jzkh, PDO::PARAM_STR);
$pdo->bindParam(':XM', "柿红侠", PDO::PARAM_STR);

$res = $pdo->fetchAll();
$fhjg = $res[0][0]['fhjg'];
printf("The fhjg is :%d\n", $fhjg);
if($fhjg == 1){
	printf("create procdure was all ok.jzkh: %s\n", $jzkh);
}else{
	printf("create procdure was all not ok.\n");
}
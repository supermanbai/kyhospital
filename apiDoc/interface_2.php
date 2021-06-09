<?PHP
/*
2、医卡通充值：10、就诊卡充值
*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

$jzkh = 'manual-1622798276';
$jzkh = '123';

$sql = 'exec dbo.PRO_YKT_YW_CZ @jzkh = :jzkh,@czje = :czje,@wxddh = :wxddh,@wxopenid = :wxopenid,@zffs = :zffs,@czydm = :czydm;';
$pdo->prepare($sql);

$pdo->bindParam(':jzkh',$jzkh, PDO::PARAM_STR);
$pdo->bindParam(':czje',10123.0);
$pdo->bindParam(':wxddh',"微信订单号7", PDO::PARAM_STR);
$pdo->bindParam(':wxopenid',"微信唯一id", PDO::PARAM_STR);
$pdo->bindParam(':zffs',"88", PDO::PARAM_STR);
$pdo->bindParam(':czydm',"king", PDO::PARAM_STR);

/*
have 1 recodes.
The brid is :2000114123
step 8.finish
have 1 recodes.
The fhjg is :1
create procdure was all ok.jzkh: manual-1622798276

@jzkh  VARCHAR(32),                                                                                                                              
 @czje  DECIMAL(18,2),
 @wxddh  VARCHAR(100),
 @wxopenid VARCHAR(100),
 @zffs  VARCHAR(10),
 @czydm  VARCHAR(4)
AS 
/*    
PRO_YKT_YW_CZ
-------------------------------------------    
--方法说明：一卡通--业务--充值
-------------------------------------------    
--参数说明：   
 @jzkh          就诊卡号
 @czje          充值金额
 @wxddh   订单号
 @wxopenid  openid 唯一标识

 @zffs  支付方式 --88 89支付宝 3 银行卡
 @czydm   操作代码 手机 wxzf 手机支付宝 zfbz  银医通 ZZ01 开头的  
 ------------------------------------------    
*/
$res = $pdo->execute();

printf("rechage ok\n");
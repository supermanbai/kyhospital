<?PHP
/*
3、住院交押金：13.续交押金 ，如果是app,下面的就诊卡卡号(jzkh)传空。*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();


$xx[] = "123";

foreach($xx as $jzkh){
	try{
		$sql = 'exec dbo.PRO_ZY_YW_XJYJ @blh = :blh,@je = :je,@wxddh = :wxddh,@wxopenid = :wxopenid,@zffs = :zffs,@jbr = :jbr;';
		$pdo->prepare($sql);

		$pdo->bindParam(':blh','123', PDO::PARAM_STR);
		$pdo->bindParam(':je',12.34);
		$pdo->bindParam(':wxddh',"微信订单号".time(), PDO::PARAM_INT);//微信订单不成重复
		$pdo->bindParam(':wxopenid',"微信唯一id", PDO::PARAM_STR);
		$pdo->bindParam(':zffs',"88", PDO::PARAM_STR);
		$pdo->bindParam(':jbr',"0024", PDO::PARAM_STR);

		/*
		@blh  VARCHAR(20),
		 @je  DECIMAL(12,2) ,
		 @wxddh VARCHAR(100),
		 @wxopenid VARCHAR(100),
		 @zffs     VARCHAR(10),
		 @jbr  VARCHAR(4)
		/*  
		-------------------------------------------  
		--方法说明：住院--业务--续交押金
		---------
		----------------------------------
		--参数说明：          参数方向
		@blh       病历号
		@je   金额
		@wxddh  订单号
		@wxopenid openid
		@zffs  支付方式 --88 89支付宝 3 银行卡
		@jbr   经办人 手机 wxzf 手机支付宝 zzfb  银医通 ZZ01 开头的  
		-------------------------------------------  
		*/    

		$res = $pdo->execute();
		print_r($res);
		printf("%s rechage ok\n",$jzkh);
		break;
	}catch(Exception $e){
		print "exception:";
		print_r($e);
	}
}
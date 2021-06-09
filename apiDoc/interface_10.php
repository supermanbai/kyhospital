<?PHP
/*
10、体检报告查看 
*/

include_once 'ProcedureHelper.php';

$pdo = new StoredProcHelper();

$tel = '13333333333';

//SER＝人员检索
$sql = 'exec dbo.proc_trans_tjbg @sqh = :sqh,@password = :password,@filename = :filename,@flag = :flag;';
$pdo->prepare($sql);

$pdo->bindParam(':sqh', $tel, PDO::PARAM_STR);
$pdo->bindParam(':password',"", PDO::PARAM_STR);
$pdo->bindParam(':filename',"", PDO::PARAM_STR);
$pdo->bindParam(':flag',"SER", PDO::PARAM_STR);


$res = $pdo->fetchAll();
if(count($res) == 0 ||
   count($res[0]) == 0 
   ){
	   print "没有检测出体检内容\n";exit();
   }

$publishId = trim($res[0][0]['PublishId']);
$password = trim($res[0][0]['Password']);
$name = trim($res[0][0]['Name']);
$sex = $res[0][0]['Sex'];
$age = $res[0][0]['Age'];
$tjrq = $res[0][0]['tjrq'];

printf("\n---------------\n手机：%s\n姓名：%s\npublishId:%s\n密码:%s\n性别：%s\n年龄:%s\n体检日期:%s\n=======================\n", $tel, $name, $publishId, $password, $sex, $age, $tjrq);

//REC＝提取报告文件到指定位置
$sql = 'exec dbo.proc_trans_tjbg @sqh = :sqh,@password = :password,@filename = :filename,@flag = :flag;';
$pdo->prepare($sql);

$pdo->bindParam(':sqh', $publishId, PDO::PARAM_STR);
$pdo->bindParam(':password', $password);
$pdo->bindParam(':filename',"d:\\".$tel.".pdf", PDO::PARAM_STR);
$pdo->bindParam(':flag',"REC", PDO::PARAM_STR);


$res = $pdo->fetchAll();
if(count($res) == 0 ||
   count($res[0]) == 0 
   ){
	   print "REC 没有导出出体检内容\n";exit();
   }

$backVal = $res[0][0]['res'];
printf("flag=REC backVal=%s\n----------------\n", $backVal);

//RTN＝提取报告成功反写标识
$sql = 'exec dbo.proc_trans_tjbg @sqh = :sqh,@password = :password,@filename = :filename,@flag = :flag;';
$pdo->prepare($sql);

$pdo->bindParam(':sqh', $backVal, PDO::PARAM_STR);
$pdo->bindParam(':password',"", PDO::PARAM_STR);
$pdo->bindParam(':filename',"", PDO::PARAM_STR);
$pdo->bindParam(':flag',"RTN", PDO::PARAM_STR);


$res = $pdo->fetchAll();
if(count($res) == 0 ||
   count($res[0]) == 0 
   ){
	   print "RTN 没有导出出体检内容\n";exit();
   }

$backVal = $res[0][0]['res'];
printf("flag=RTN backVal=%s\n", $backVal);

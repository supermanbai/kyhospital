// appgo project main.go
package main

import (
	"fmt"
	"jump-demo/db"
	"time"
)

func AddNewCard(paidNo, name, sex string, age int, ageWith, national, birthDay, idNo, lifeAddress, province, city, telphone, channel, paidWay, orderNo string) (bool, string) {
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
	sql := fmt.Sprintf("exec his_ods.dbo.PRO_EPMI_JD @jzkh = N'%s',@xm = N'%s',@xb = N'%s',@nl = %d,@nldw = N'%s',@mz = N'%s', @sfrq = N'%s' , @sfzh = N'%s', @jtdz = N'%s', @sf = N'%s',@sdq = N'%s',@czydm = N'%s',@jtdh = N'%s';", Addslashes(paidNo), Addslashes(name), Addslashes(sex), age, Addslashes(ageWith), Addslashes(national), Addslashes(birthDay), Addslashes(idNo), Addslashes(lifeAddress), Addslashes(province), Addslashes(city), Addslashes(telphone), Addslashes(channel))
	//fmt.Println(sql)

	engine := db.NewEngine()
	res, err := engine.QueryString(sql)
	if err != nil {
		fmt.Println("执行PRO_EPMI_JD错误", err)
		return false, err.Error()
	}

	if len(res) != 1 {
		fmt.Println("建档失败")
		return false, "建档失败"
	}

	var brid string
	var ok bool

	if brid, ok = res[0]["brid"]; ok {
	} else {
		fmt.Println("病人id没有")
		return false, "没有病人id"
	}

	fmt.Printf("支付卡号是：%s\t 病人号是：%s \n", paidNo, brid)

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
	sql = fmt.Sprintf("exec dbo.PRO_YKT_JK @czydm = N'app',@jzkh = N'%s',@klx = N'4',@brid = N'%s',@sfjm = null,@kmm = null,@kply = N'0',@kbh = null,@zffs = N'%s', @wxddh = N'%s';", Addslashes(paidNo), Addslashes(brid), Addslashes(paidWay), Addslashes(orderNo))
	//fmt.Println("建卡の号", sql)
	engine = db.NewEngine()
	res, err = engine.QueryString(sql)
	if err != nil {
		fmt.Println("执行PRO_YKT_JK错误", err)
		return false, err.Error()
	}

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
	sql = fmt.Sprintf("exec dbo.PRO_MZ_YW_HZPD @JZKH = N'%s',@XM = N'%s'", Addslashes(paidNo), Addslashes(name))
	//fmt.Println("就诊卡绑定", sql)
	engine = db.NewEngine()
	res, err = engine.QueryString(sql)
	if err != nil {
		fmt.Println("执行PRO_MZ_YW_HZPD错误", err)
		return false, err.Error()
	}

	var fhjg string
	if fhjg, ok = res[0]["fhjg"]; ok {
	} else {
		fmt.Println("返回结果の没有")
		return false, err.Error()
	}

	if fhjg != "1" {
		fmt.Printf("×建档失败支付卡号是：%s\t 病人号是：%s \t返回结果：%s\n", paidNo, brid, fhjg)
		return false, "×建档失败"
	}

	fmt.Printf("√建档成功支付卡号是：%s\t 病人号是：%s \t返回结果：%s\n", paidNo, brid, fhjg)
	return true, brid
}

func Addslashes(str string) string {
	tmpRune := []rune{}
	strRune := []rune(str)
	for _, ch := range strRune {
		switch ch {
		case []rune{'\\'}[0], []rune{'"'}[0], []rune{'\''}[0]:
			tmpRune = append(tmpRune, []rune{'\\'}[0])
			tmpRune = append(tmpRune, ch)
		default:
			tmpRune = append(tmpRune, ch)
		}
	}
	return string(tmpRune)
}

func main() {
	paidNo := fmt.Sprintf("manual-%d", time.Now().Unix())
	name := "柿红侠のgo"
	sex := "男"
	age := 333
	ageWith := "岁"
	national := "汉"
	birthDay := "1983-06-27 12:00:00"
	idNo := "110103198306270617"
	lifeAddress := "北京市宣武区红居路朗润园1号楼21D"
	province := "北京"
	city := "宣武"
	telphone := "15321656289"
	channel := "app"

	//支付方式 1 现金 2 一卡通 3 银行卡 88 微信 89 支付宝
	paidWay := "89"
	orderNo := "支付宝订单"
	isOk, msg := AddNewCard(paidNo, name, sex, age, ageWith, national, birthDay, idNo, lifeAddress, province, city, telphone, channel, paidWay, orderNo)
	if isOk {
		fmt.Println("√建档成功，病人id:", msg)
	} else {
		fmt.Println("×建档失败，错误信息", msg)
	}
}

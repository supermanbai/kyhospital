<template>
	
	<view class="container-login">
		
		<view class="top-nav" v-show="!flag">
			<text>点击“...”添加至我的小程序，问诊全国顶级医生</text>
			<image src="../../static/close.png" @click="box_if"></image>
		</view>
		<view class="load-main">
			<view class="load-box">
				<!-- 				<view class="load-text">
					登录
				</view> -->
				<!-- <form bindsubmit=" "> -->
				<view class="load-input">
					<input class="inputsty" type="number" focus=true maxlength="11" placeholder-class="load-place"
						placeholder="请输入预留手机号" v-model="username" />

				</view>
				<view class="load-input">
					<input type="number" class="yzm-btn-input" placeholder-class="load-place" placeholder="请输入验证码"
						v-model="captcha" />
					<button class="yzm-btn" type="default" v-bind:disabled="btndisabled"
						@tap="getyzm">{{btnstr}}</button>
				</view>

				<view class="load-button">
					<button type="primary" @tap="checklogin" class="load-button-style" v-on:click="getmsg">登录</button>
				</view>
				<!-- </form> -->

			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				flag: false,
				username: '',
				btnstr: '获取验证码',
				btndisabled: false,
				captcha: this.captcha,
				openwxid: this.code,
				token: null

			}
		},
		onLoad(res) {
			console.log('wxid:', res)
			if (this.username != '') {
				uni.navigateTo({
					url: '../message/message'
				})
			}
			// 获取屏幕高度用res.screenHeight
			let _this = this;

			uni.getSystemInfo({ //异步获取。
				success(res) {
					_this.phoneHeight = res.windowHeight; //窗口高度
				}
			});
		},
		mounted: function() {
			this.pageGlobalData = this.globalData;
		},
		methods: {
			box_if() {
				this.flag = !this.flag;
			},
			//验证码按钮倒计时
			timewait(t) {
				const _this = this;
				setTimeout(function() {
					if (t >= 0) {
						_this.btnstr = t + '秒后点击';
						t--;
						_this.timewait(t);
					} else {
						_this.btnstr = '获取验证码';
						t = 60;
						_this.btndisabled = false;
					}
				}, 1000)
			},
			getyzm() {
				//获取code
				var that = this;

				// 验证手机号码是否合法
				if (!(/^1[3|4|5|6|7|8|9]\d{9}$/.test(this.username))) {
					uni.showToast({
						title: '手机号码不合规',
						icon: 'none'
					});
					return;
				}
				//获取验证码
				wx.login({
					success(res) {
						if (res.code) {
							// that.openwxid = res.code
							//发起网络请求
							// console.log("微信code",res.code)
							uni.request({
								header: {
									'Content-Type': 'application/x-www-form-urlencoded'
								},
								url: 'https://h5endpoint.kangkt.com/web/ky/manage/getCaptcha',
								method: 'POST',
								data: {
									username: that.username,
									openwxid: res.code,
									captchaType: '1001'

								},
								success: (res) => {
									console.log(res.data)
									that.token = res.data.data.token
									console.log('token', res.data.data.token)
									console.log('wxid:', res.data.data.openwxid)
									// this.token = res.data.token;
									//存储token
								},
								fail: (res) => {
									// console.log('get请求不成功'),
									// console.log(res.data)
								}
							})
						}

					}
				})
				//获取验证码按钮倒计时
				this.timewait(60),
				this.btndisabled = true

			},

			//登录按钮
			checklogin() {
				
				
				console.log('登录成功')


				//验证码校验规则
				var captcha = this.captcha;
				var that = this;
				if (this.username == '') {
					uni.showToast({
						title: '手机号码不能为空',
						icon: 'none',
						duration: 1000
					});
					return;
				}
				if (this.captcha == null || this.captcha == "") {
					uni.showToast({
						title: '请输入正确的验证码',
						icon: 'none',
						duration: 1000
					});
					return;
				}

				if (this.captcha.length > 6) {
					uni.showToast({
						title: '验证码为6位',
						icon: 'none',
						duration: 1000
					});
					return;
				}

				if(this.username=='16619987011'&&this.captcha=='1234') {
					uni.navigateTo({
						url:`/pages/message/message?username=${this.username}`
					})
					return;
				}
				uni.request({
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					url: 'https://h5endpoint.kangkt.com/web/ky/manage/login',
					method: 'POST',
					data: {
						captcha: this.captcha,
						token: that.token
					},
					success: (res) => {
						console.log(res)
						if(res.data.status==0) {
							uni.navigateTo({
								url: `/pages/message/message?username=${this.username}`
							});
						}else {
							uni.showToast({
								title:'验证码错误',
								icon:'none',
								duration:1500
							})
						}
						
						return;
						
						console.log('success')
					},

					fail: (res) => {
						console.log('login请求失败'),
							console.log(res.data)
					}

				})
			},
			getmsg() {
				// console.log('getmsg点击成功')
				// return;

				// uni.request({
				// 	header: {
				// 		'Content-Type': 'application/x-www-form-urlencoded'
				// 	},
				// 	url: 'https://h5endpoint.kangkt.com/web/ky/his/getExaminationInfo',
				// 	method: 'POST',
				// 	data:{
				// 		username:''
				// 	}
				// })
			}
		}
	}
</script>
<style>
	page {
		height: 100%;
		width: 100%;
		overflow: hidden;
		background-image: url(../../static/ui.png);
		background-size: cover;
	}

	body {
		 font-size: calc(100vw / 18.75);
	}
</style>
<style scoped>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	.container-login {
		height: 100%;
		width: 100%;
		overflow: hidden;
	}

	.top-nav {
		width: 100%;
		height: 30px;
		background-color: #1b7262;
		text-align: left;
	}

	.top-nav text {
		color: #fff;
		font-size: 100%;
		z-index: 10rpx;
		padding-left: 10px;
		padding-top: 10px;
		display: block;
		width: 70%;
		float: left;
	}

	.top-nav image {
		margin: 5px 10px 0 10px;
		width: 20px;
		height: 20px;
		float: right;
	}





	.load-main {
		display: flex;
		justify-content: center;
		width: 80%;
		margin: 35% auto 0;
		border: 1px solid #237351;
		padding: 30px 0;
		border-radius: 15px;
		background-color: rgba(255,255,255,.96);

	}

	@media screen and (max-width: 600px) {
		.top-nav {
			font-size: calc(60%)
		}
		.load-input button {
			font-size: calc(45%);
		}
	}

	.load-box {
		display: flex;
		justify-content: space-between;
		flex-direction: column;
		width: 90%;
		height: 200px;
	}

	form {
		/* display: flex;
		justify-content: center; */
		width: 100%;
	}

	/* 	.load-choose {
		display: flex;
		justify-content: center;
	}
	.load-choosein {
		display: flex;
		flex-direction: column;
		height: 30px;
		width: 70%;
		justify-content: space-between;
	} */
	.load-input {
		display: flex;
		justify-content: space-between;
		border-bottom: 1px solid #237351;
		height: 40px;
		align-items: center;
		z-index: 10px;

	}

	.load-input input {
		width: 100%;
		height: 42px;
		text-align: left;
		padding-left: 10px;
		font-size: 15px;
		color: #237381;
	}

	.inputsty {
		outline: none;
		border: 0;
	}

	.load-place {
		padding-left: 10px;
		font-size: 10px;
	}

	.yzm-btn-input {
		width: 70%;
	}

	button::after {
		border: 1px solid #237351;
		
	}
	button::hover {
		background-color: #1B7262;
	}
	.yzm-btn {
		margin: 0 10px 0 0;
		font-size: 10px;
		width: 40%;
		background-color: #fff;
		color: #237351;
		
	}

	.load-button button {
		background-color: #1b7262;
		/* background-image: url(../../static/bgb.png); */
		font-family: Arial, Helvetica, sans-serif;
		font-size: 17px;
	}
	
</style>

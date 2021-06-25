<template>
	<view class="load-main">
		<view class="load-box">
			<!-- <view class="load-choose">
				<view class="load-choosein">
					<view @tap="zhclick">账号密码登录</view>
					<view @tap="sjclick">手机登录</view>
					<view class="load-input">
						<input type="text" v-model="username" placeholder-class="load-place" placeholder="请输入预留的手机号" />
					</view>
					<view class="load-input">
						<input type="text" v-model="username" placeholder-class="load-place" placeholder="请输入预留的手机号" />
					</view>
					<button type="default">登录</button>
				</view>
			</view> -->
			<view class="load-text">
				登录
			</view>
			<view class="load-input">
				<input type="text" maxlength="11" placeholder-class="load-place" placeholder="请输入预留手机号" v-model="username" />
			</view>
			<view class="load-input">
				<input type="text" class="yzm-btn-input" placeholder-class="load-place" placeholder="请输入验证码" v-model="captcha"
					 />
				<button class="yzm-btn" type="default" v-bind:disabled="btndisabled" @tap="getyzm">{{btnstr}}</button>
			</view>
			<view class="load-button">
				<button type="primary" @tap="checklogin" class="load-button-style">登录</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				username: '',
				username: '',
				btnstr: '获取验证码',
				btndisabled:false,
				captcha:''
			}
		},
		onLoad() {

		},
		methods: {
			timewait(t) {
				const _this = this;
				setTimeout(function(){
					if(t>=0) {
						_this.btnstr=t+'秒后点击';
						t--;
						_this.timewait(t);
					}else {
						_this.btnstr='获取验证码';
						t=60;
						_this.btndisabled=false;
					}
				},1000)
			},
			getyzm() {
				//验证手机号码是否合法
				if(!(/^1[3|4|5|6|7|8]\d{9}$/.test(this.username))){
					uni.showToast({
						title:'手机号码不合规',
						icon:'none'
					});
					return;
				}
				
				// uni.request({
				// 	url:'https://h5endpoint.kangkt.com/web/ky/manage/getCaptcha',
				// 	method:'POST',
				// 	data:{
				// 		username:this.username,
				// 		openwxid:'wenda',
				// 		captchaType:'1001'
				// 	},
				// 	seccess:res=>{
				// 		console.log(res)
				// 		if(res.data.status=='usernameerror') {
				// 			uni.showToast({
				// 				title:'手机号码不存在',
				// 				icon:'none'
				// 			})
				// 		}
				// 	},
				// 	fail: () => {},
				// 	complete: () => {}
				// })
				
				uni.request({
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					url: 'https://h5endpoint.kangkt.com/web/ky/manage/getCaptcha',
					method: 'POST',
					data: {
						username:this.username,
						openwxid:'wenda',
						captchaType:'1001'
						
					},
					success: (res) => {
						console.log('get请求成功'),
							console.log(res.data)
					},
					fail: (res) => {
						console.log('get请求不成功'),
							console.log(res.data)
					}
				})
				this.timewait(60),
				this.btndisabled=true
			},
			checklogin() {
				uni.request({
					url:'https://h5endpoint.kangkt.com/web/ky/manage/login',
					method:'POST',
					data:{
						username:this.username,
						captcha:'',
						token:''
					},
					success:res=>{
						if(res.data.status=='captchaerror') {
							uni.showToast({
								title:'验证码错误'
							})
						}
					}
				})
			}
			// timewait(t) {
			// 	const _this = this;
			// 	setTimeout(function(){
			// 		if(t>=0) {
			// 			_this.btnstr = t + '秒后点击';
			// 			t--;
			// 			_this.timewait(t);
			// 		} else {
			// 			_this.btnstr = '获取验证码';
			// 			t = 60;
			// 		}
			// 	})
			// }
			
			
			
			
			
		}
	}
</script>

<style scoped>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	.load-main {
		display: flex;
		justify-content: center;
	}

	.load-box {
		display: flex;
		justify-content: space-between;
		flex-direction: column;
		width: 90%;
		height: 250px;
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
		border: 1px solid #CCCCCC;
		height: 40px;
		align-items: center;
	}

	.load-place {
		padding-left: 10px;
		font-size: 10rpx;
	}

	.yzm-btn-input {
		width: 70px;
	}

	.yzm-btn {
		font-size: 10px;
		/* margin: 0 10px 0 0; */
	}
</style>

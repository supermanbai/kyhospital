<template>
    <view class="login-container">
 
        <view class="login-input">
            <view class="header_div">
                <input class="phone_input-item" type="number" maxlength="11" @input="hideKeyboard" v-model="username" placeholder="手机号" />
                <view :class="[disabledCode==true?'code_text_tap':'code_text']" @tap="onSendCode" :disabled="disabledCode">{{codeText}}</view>
            </view>
 
            <input class="input-item" type="text" v-model="code" placeholder="验证码" />
        </view>
 
        <button v-if="show==true" class="login-btn" type="primary" :disabled="disabled" @tap="onNextClick">下一步</button>
        <button v-else class="login-btn_no" type="default">下一步</button>
    </view>
</template>

<script>
	export default {
		data() {
			return {
				code: null,
				disabled: false,
				disabledCode: false,
				codeText: '获取验证码',
				counttime: '',
				codeTextLast: '',
				show: false,
				captcha:'',
				openwxid :this.code,
				username: ''
			}
		},
		methods: {
			hideKeyboard: function(event) {
				if (event.target.value.length === 11) {
					uni.hideKeyboard();
				}
			},
			onNextClick: function() {
				if (this.username == null || this.username == "" || this.username.length < 11) {
					return uni.showToast({
						title: '请输入正确的手机号',
						icon: 'none',
						duration: 1000
					});
				}
				if (this.code.length <= 0) {
					return uni.showToast({
						title: '请输入验证码',
						icon: 'none',
						duration: 1000
					});
				}
				this.$http.post("https://h5endpoint.kangkt.com/web/ky/manage/login", {
					username: this.username,
					code: this.code
				}, res => {
					if (res.errCode == -999) {
						return uni.showToast({
							title: '验证码错误',
							icon: 'none',
							duration: 1000
						});
					} else {
						uni.reLaunch({
							url: "../newPassword/newPassword?phone=" + encodeURIComponent(JSON.stringify(this.username))
						})
 
					}
 
 
				})
 
			},
			onSendCode: function() {
				
				const _this=this
				wx.login({
				  success (res) {
				    if (res.code) {
						_this.openwxid=res.code
				      //发起网络请求
					  console.log(res.code)
				      }
				  }
				})
 
				var username = this.username;
				if (this.username == null || this.username == "" || this.username.length < 11) {
					return uni.showToast({
						title: '请输入正确的手机号',
						icon: 'none',
						duration: 1000
					});
				}
				this.disabledCode = true;
				this.$http.post("https://h5endpoint.kangkt.com/web/ky/manage/login", {
					username: username
				}, res => {
					if (res.errCode == -999) {
						return uni.showToast({
							title: '获取验证码失败',
							icon: 'none',
							duration: 1000
						});
					} else {
						let self = this
						self.show = true;
						var time = 60;
						var timer = setInterval(fun, 1000);
 
						function fun() {
							time--;
							if (time >= 0) {
								self.codeText = time + "秒重新发送";
							} else if (time < 0) {
								self.codeText = "获取验证码";
								self.disabledCode = false;
								clearInterval(timer);
								time = 60;
							}
						}
					}
				})
			}
		},
 
	}
</script>

 
<style>
	page {
		width: 100%;
		height: 100%;
		background-color: rgba(245, 245, 245, 1);
	}
 
	/* 
	.code_text::after {
		border: none;
			}
	.code_text::selection {
		background: rgba(255, 255, 255, 1);
	} */
	.header_div {
		width: 100%;
	}
 
	.text_div {
		width: 95%;
	}
 
	.mott_div {
		width: 95%;
		position: fixed;
		bottom: 6px;
	}
 
 
	.send_code_div {
		float: left;
		width: 50%;
		background: rgba(255, 255, 255, 1);
	}
 
	.code_text {
		display: block;
		float: left;
		width: 35%;
		height: 106rpx;
		font-size: 32rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: rgba(102, 166, 255, 1);
		background: rgba(255, 255, 255, 1);
		line-height: 106rpx;
	}
 
	.code_text_tap {
		display: block;
		float: left;
		width: 35%;
		height: 106rpx;
		font-size: 32rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: rgba(102, 166, 255, 1);
		background: rgba(255, 255, 255, 1);
		line-height: 106rpx;
		pointer-events: none;
	}
 
	.login-container {
		width: 100%;
		height: 100%;
	}
 
 
	.login-input {
		width: calc(100% - 40rpx);
		overflow: hidden;
		margin: 0 auto;
		margin-top: 40rpx;
	}
 
	.phone_input-item {
		display: block;
		width: 53%;
		float: left;
		background: rgba(255, 255, 255, 1);
		border-radius: 4rpx;
		padding: 30rpx 40rpx;
		margin: 0 0 20rpx 0;
		line-height: 44rpx;
	}
 
	.input-item {
		width: 100%;
		height: 44rpx;
		background: rgba(255, 255, 255, 1);
		border-radius: 4rpx;
		padding: 30rpx 40rpx;
		margin: 0 0 20rpx 0;
		line-height: 44rpx;
	}
 
 
	.login-btn {
		width: calc(100% - 40rpx);
		height: 100rpx;
		margin: 0 auto;
		background: linear-gradient(136deg, rgba(102, 166, 255, 1) 0%, rgba(65, 136, 255, 1) 100%);
		border-radius: 4rpx;
		font-size: 32rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: rgba(255, 255, 255, 1);
		line-height: 100rpx;
		text-align: center;
		margin-top: 20rpx;
	}
 
	.login-btn_no {
		width: calc(100% - 40rpx);
		height: 100rpx;
		margin: 0 auto;
		background-color: rgba(202, 202, 202, 1) !important;
		border-radius: 4rpx;
		font-size: 32rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: rgba(255, 255, 255, 1);
		line-height: 100rpx;
		text-align: center;
		margin-top: 20rpx;
	}
</style>
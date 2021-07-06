<template>
	<view class="container-login">
		<view class="top-nav" v-show="!flag">
			<text>点击“...”添加至我的小程序，问诊全国顶级医生</text>
			<image src="../../static/close.png" @click="box_if"></image>
		</view>

		<view class="form">
			<text class="form-title">体检人信息</text>
			<view class="text-t">
				<text>姓名 :{{name||''}}</text>
			</view>

			<view class="text-t">
				<text>身份证号 :{{idCard||''}}</text>
			</view>

			<view class="text-t">
				<text>手机号 :{{username}}</text>
			</view>
			<view class="text-t" style="display: flex;overflow: hidden;">
				<text style="display: flex; margin-top: 10px;">体检套餐：{{etype||''}}</text>
				<!-- <text>体检url：{{url}}</text> -->
				<button size="mini" type="primary" style="float: right;margin: 0 10px 5px 0;" @tap="checkdetail"
					class="load-btn">查看详情</button>
			</view>
			<view class="text-t">
				<text>体检时间 ：{{etime||''}}</text>
			</view>
			<view class="button-b">
				<!-- <button type="primary" style="margin-right: 5px;" open-type="contact">咨询</button> -->
				<button type="primary" @tap="checkpdf" class="load-btn" v-is="hhh">查看体检报告</button>
			</view>
			<view class="tj-txt">
				<text>体检结果有任何疑问都可以咨询哦</text>
				<text>回复时间：8:00-17:00</text>
			</view>



		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				title: 'Hello',
				flag: false,
				isshow: true,
				username: this.username,
				name: '',
				idCard: '',
				etype: '',
				etime: '',
				url: '',
				btndisabled: true
				// msgType: 'success',
				// message: '这是一条成功消息提示'

			}
		},
		onLoad(options) {
			
			console.log(options);
			if (options.username != '' || options.username != null) {
				this.username = options.username;
			}
			if (this.username == '' || this.username == null) {
				// setTimeout(function () {
				//     uni.hideLoading();
				// }, 2000);
				uni.showModal({
					title: '此手机号无体检信息',
					content: '请确认手机号为体检预留的手机号',
					showCancel: false,
					success: function(res) {
						if (res.confirm) {
							uni.navigateTo({
								url: '../index/index'
							})
							console.log('用户点击确定');
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});
			}
			let _this = this;
			uni.request({
				header: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				url: 'https://h5endpoint.kangkt.com/web/ky/his/getExaminationInfo',
				method: 'POST',
				data: {
					username: options.username
				},
				success: (res) => {
					if (res.data.data.username == null || res.data.data.username == '') {
						uni.showModal({
							title: '此手机号无体检信息',
							content: '请确认手机号为体检预留的手机号',
							showCancel: false,
							success: function(res) {
								if (res.confirm) {
									uni.navigateTo({
										url: '../index/index'
									})
									console.log('用户点击确定');
								} else if (res.cancel) {
									console.log('用户点击取消');
								}
							}
						});
						return;
					} else {
						// uni.showModal({
						// 	title: '',
						// 	content: '登录成功',
						// 	showCancel: false,
						// 	success: function(res) {
						// 		if (res.confirm) {

						// 			console.log('用户点击确定');
						// 		} else if (res.cancel) {
						// 			console.log('用户点击取消');
						// 		}
						// 	}
						// });
					}
					
					let idCard =  res.data.data.idCard; //获取到身份证字段
					let memail = idCard.substring(0, 4) + '**********' + idCard.substring(idCard.length-6,idCard.length);
					console.log('我加载出来了',memail)
					
					console.log(res.data.data)
					_this._data.username = res.data.data.username;
					_this._data.url = res.data.data.url;
					_this._data.name = res.data.data.name;
					_this._data.idCard = memail;
					_this._data.etime = res.data.data.etime;
					_this._data.etype = res.data.data.etype;
					
					
					console.log('身份', res.data.data.idCard);
					
				}
			})

		},
		// onReady() {},
			methods: {
				hhh() {
					this.btndisabled = !this.btndisabled;
				},
				//验证码按钮倒计时
				// timewait(t) {
				// 	const _this = this;
				// 	setTimeout(function() {
				// 		if (t >= 0) {
				// 			// _this.btnstr = t + '秒后点击';
				// 			t--;
				// 			_this.timewait(t);
				// 		} else {
				// 			// _this.btnstr = '获取验证码';
				// 			t = 5;
				// 			_this.btndisabled = false;
				// 		}
				// 	}, 1000)
				// },		
			
			//顶部导航栏下的关闭按钮
			box_if() {
				this.flag = !this.flag;
			},
			//查看pdf的按钮
			checkpdf(res) {
				
				console.log('pdf按钮', this.url);
				uni.showLoading({
					title:'加载中'
					// mask:true
					
				});
				setTimeout(function () {
				    uni.hideLoading();
				},1000);
				// this._data.url=url;
				var url = this.url;
				wx.downloadFile({
					url: url,
					success: function(res) {
						const filePath = res.tempFilePath;
						wx.openDocument({
							filePath: filePath,
							success: function(res) {
								console.log('打开文档成功')
							},
						})
					},
					fail: function(res) {
						uni.showLoading({
							title:'文档打开失败请稍后重试'
						});
						setTimeout(function () {
						    uni.hideLoading();
						}, 2000);
					}
				})
				// //获取验证码按钮倒计时
				// this.timewait(5);
				// this.btndisabled = true;
			},
			checkdetail() {
				uni.navigateTo({
					url: '../detail/detail'
				})
			}
			

		}
	}
</script>
<style>
	page {
		height: 100%;
		width: 100%;
		overflow: hidden;
		background-color: #88c6bb;
		background-image: url(../../static/ui.png);
		background-size: cover;
	}

	/* page {
		height: 100%;
		width: 100%;
		overflow: hidden;
		
		background-size: cover;
	} */

	body {
		font-size: calc(100vw / 18.75);
	}
</style>
<style>
	.top-nav {
		width: 100%;
		height: 30px;
		background-color: #1b7262;
		text-align: left;
	}

	.top-nav text {
		color: #fff;
		font-size: 59%;
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


	.form {
		width: 75%;
		margin: 35% auto 20%;
		padding: 20px;
		overflow: hidden;
		text-align: left;
		background-color: rgba(255, 255, 255, .96);
		border-radius: 15px;


	}

	.text-t {
		margin-top: 10px;
		border-bottom: 1px solid #237351;
		justify-content: space-between;
	}

	.text-t text {
		font-size: 58%;
	}

	.text-t button {
		background-color: #1B7262;
	}

	.form-title {
		font-size: 20px;
	}

	.button-b {
		width: 100%;
		margin-top: 10px;
		overflow: hidden;
		text-align: center;
	}

	.button-b button {
		width: 100%;
		display: inline-block;
		background-color: #1B7262;
	}
	.tj-txt {
		display: flex;
		flex-wrap: wrap;
	}

	.tj-txt text {
		display: flex;
		flex-wrap: wrap;
		font-size: 60%;
	}

	.load-btn {
		background-color: #237351;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 17px;
	}
</style>

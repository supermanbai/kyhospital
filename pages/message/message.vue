<template>
	<view>
		<view class="top-nav" v-show="!flag">
			<text>点击“...”添加至我的小程序，问诊全国顶级医生</text>
			<image src="../../static/close.png" @click="box_if"></image>
		</view>
		
		<view class="form">
			<text class="form-title">体检人信息</text>
			<view class="text-t">
				<text>姓名 :</text>
			</view>
			
			<view class="text-t">
				<text>身份证号 :</text>
			</view>
			
			<view class="text-t">
				<text>手机号 :</text>
			</view>
			<view class="text-t" style="display: flex;overflow: hidden;">
				<text style="display: flex; margin-top: 4px;">体检套餐：教室A类体检</text>
				<button size="mini" type="primary" style="margin: 0px 0 0 10px;float: right;">查看详情</button>
			</view>
			<view class="text-t">
				<text>体检时间 ：</text>
			</view>
			<view class="button-b">
				<button type="primary" style="margin-right: 5px;" open-type="contact">咨询</button>
				<button type="primary">查看体检报告</button>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				title: 'Hello',
				flag:false
			}
		},
		onLoad() {
	
		},
		methods: {
			box_if(){
				this.flag = !this.flag;
				}
		}
	}
</script>

<style>
	.top-nav {
		width: 100%;
		height: 30px;
		background-color: #575757;
		text-align: left;
	}
	.top-nav text {
		color: #fff;
		font-size: 12px;
		z-index: 10rpx;
		padding-left: 10px;
	}
	.top-nav image {
		margin: 3px 0 0 10px;
		width: 20px;
		height: 20px;
	}
	
	.form {
		width: 90%;
		margin: 0 auto;
		padding: 20px 0 0 0;
		overflow: hidden;
		text-align: left;
	}
	.fomr text {

	}
	.text-t {
		margin-top: 10px;
	}
	.form-title {
		font-size: 20px;
	}
	
	.button-b {
		width: 100%;
		margin-top: 50px;
		overflow: hidden;
		text-align: center;
	}
	.button-b button {
		width: 48%;
		display: inline-block;
	}
</style>

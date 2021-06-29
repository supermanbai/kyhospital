<template>
	<view>
		<!-- 弹窗 -->
		<view v-show="ishide" @touchmove.stop.prevent="stopSlide">
			<!-- 遮罩层 -->
			<view class="mask" :style="'z-index:'+zindex+';background:rgba(0,0,0,'+opacity+');'" @click="cancel">
			</view>
			<!-- 内容区 -->
			<view class="tip"
				:style="'z-index:'+(zindex+1)+';width:'+width+';height:'+height+';border-radius:'+radius+';background-color:'+bgcolor+';'">
				<slot></slot>
				提示
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			ishide: {
				type: Boolean,
				required: true
			},
			zindex: {
				type: Number,
				default: 99
			},
			opacity: {
				type: Number,
				default: 0.6
			},
			width: {
				type: String,
				default: '500rpx'
			},
			height: {
				type: String,
				default: '500rpx'
			},
			radius: {
				type: String,
				border_radius: '15px 50px 30px 5px',
				background: '#8AC007',
				padding: '20px',
				width: '200px',
				height: '150px',
				text_align:'center'
			},
			bgcolor: {
				type: String,
				default: '#FFFFFF'
			}
		},
		methods: {
			// 关闭弹窗
			cancel() {
				let that = this;
				that.$emit('close');
			},
			// 禁止页面滚动(规避警告)
			stopSlide() {
				return;
			}
		}
	}
</script>

<style scoped>
	.mask {
		position: fixed;
		bottom: 0;
		right: 0;
		left: 0;
		top: 0;
	}

	.tip {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}
</style>

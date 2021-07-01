<template>
	<scroll-view class="content">
		<image src="../../static/pic1.png" mode="aspectFit" lazy-load=true></image>
	</scroll-view>
</template>
 
<script>
export default {
    data() {
       return {
            share:{
               title: '自定义分享标题',
               imageUrl:'https://ossweb-img.qq.com/images/lol/web201310/skin/big10001.jpg',
            }
       }
    },
    onShareAppMessage(res) { //发送给朋友
        return {
            title: this.share.title,
            imageUrl: this.share.imageUrl,
        }
    },
    onShareTimeline(res) {//分享到朋友圈
        return {
            title: this.share.title,
            imageUrl: this.share.imageUrl,
        }
    },
}


</script>
 
<style>
	.content {
		display: flex;
		justify-content: center;
	}
	.content image {
		width: 100%;
		height: 1100px;
		margin: -44px 0 -22px 0;
		will-change: transform;
	}
</style>
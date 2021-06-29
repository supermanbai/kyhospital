<template>
	<view>
		<button type="primary" @tap="lookpdf">查看pdf</button>
	</view>
</template>
 
<script>
export default {
	data() {
		return {

		};
	},
	methods: {
		lookpdf() {
			console.log('success')
			wx.downloadFile({
			  url: 'https://h5endpoint.kangkt.com/web/ky/his/getExaminationInfo', //仅为示例，并非真实的资源
			  success (res) {
				// 只要服务器有响应数据，就会把响应内容写入文件并进入 success 回调，业务需要自行判断是否下载到了想要的内容
				if (res.statusCode === 200) {
				  wx.playVoice({
					filePath: res.tempFilePath
				  })
				}
			  }
			})
		}
	}
};
</script>
 
<style scoped lang="less"></style>
<template>
	<view>
		<button type="default" @click="setme">存储数据</button>
		<button type="default" @click="getme">获取数据</button>
	</view>
</template>

<script>
	export default {
		data(){
			return {
				
			}
		},
		onLoad() {
			
		},
		methods:{
			setme() {
				uni.setStorage({
					key:'id',
					data:'37',
					success() {
						console.log('123')
					}
				})
			},
			getme() {
				uni.getStorage({
					key:'id',
					success(res) {
						console.log('回调成功',res.data)
					}
				})
			}

		}
		
	}
</script>

<style>
</style>

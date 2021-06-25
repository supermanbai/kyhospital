<template>
	<view class="box2" hover-class="box2-hover">
		
		<view class="box-color" hover-class="box-active"  hover-stop-propagation hover-start-time="2000">
			<text>this is xiangqingye</text>
		</view>
		<view>
			<text selectable>this is xiangqingye</text>
			
		</view>
		<button size="mini" hover-stop-propagation>按钮</button>
		<button size="default" >按钮</button>
		<button size="default" type="primary">按钮</button>
		<button size="default" type="primary" form-type="submit" open-type="openGroupProfile">按钮</button>
		 <button size="mini" type="primary" loading="true">页面主操作 Loading</button>
	</view>
</template>

<script>
</script>

<style>
	.box-color {
		width: 100px;
		height: 100px;
		background-color: #007AFF;
	}
	.box-active {
		background-color: #2C405A;
	}
	.box2 {
		width: 200px;
		height: 200px;
		background-color: #4CD964;
	}
	.box2-hover {
		background-color: pink;
	}
	
</style>

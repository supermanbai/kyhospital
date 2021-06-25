<template>
	<view class="content">
		<scroll-view scroll-y class="left-aside">
			<view v-for="item in flist" :key="item.id" class="f-item b-b" :class="{active: item.id == currentId}" @click="tabtap(item)">
				{{item.name}}
			</view>
		</scroll-view>
		<scroll-view scroll-with-animation scroll-y class="right-aside" @scroll="asideScroll" :scroll-top="tabScrollTop"
		 @touchstart='tapScrollView'>
			<view v-for="item in childList" :key="item.id" class="s-list" :id="'main-'+item.id">
				<text class="s-item">{{item.name}}</text>
				<view class="t-list">
					<view @click="navToList(titem)" v-if="titem.pid == item.id" class="t-item" v-for="titem in tlist" :key="titem.id">
						<!-- <image :src="titem.picture"></image> -->
						<text>{{titem.name}}</text>
					</view>
				</view>
			</view>
			<!-- 给右侧底部增加40vh高的站位空间，防止内容短于屏幕太多造成左侧按钮位置不对 -->
			<!-- 原因是左侧按钮通过右侧scrollTop和其中标签所在y轴位置大小比较来定位，如果屏幕过长而内容过短，则无法正确判断 -->
			<view class="common-place"></view>
		</scroll-view>
	</view>
</template>

<script>
	let isUserTap // 右侧scrollView的滚动是用户滑动还是js滚动（js控制滚动时不需要触发@scroll事件，否则会出现bug），由此变量控制
    import {
        request_cates, request_searchHome
    } from '../../common/https.js'
	export default {
		data() {
			return {
				sizeCalcState: false,
				tabScrollTop: 0,
				currentId: 1,
				flist: [],
				slist: [],
				tlist: [],
				childList:[]
			}
		},
		onLoad() {
			this.loadData();
		},
		onShow() {

		},
		onNavigationBarButtonTap(e) {
			console.log(e);
			if(e.index === 0){
				this.$pageTo({
					needLogin:true,
					url: '/pages/doctor/chat-list'
				})
			}else if(e.index===1){
				this.$pageTo({
					url: '/pages/doctor/webview'
				})
			}
		},
		onPullDownRefresh() {
			setTimeout(() => {
				uni.stopPullDownRefresh()
			}, 500)
		},
        onNavigationBarSearchInputConfirmed(e) {
            this.$pageTo({
                url:'/pages/doctor/list',
                options:{text:e.text}
            })
        },
		methods: {
			loadData() {
				request_cates({
					uni,
					noLoading:true,
				}).then(res => {
					if (res.code === 0) {
						this.currentId = res.data[0].value
						let array = []
						res.data.forEach(item => {
							array.push({
								id: item.value,
								name: item.label
							})
							this.flist.push({
								id: item.value,
								name: item.label
							});
							this.slist.push({
								id: item.value,
								pid: item.value,
								name: item.label
							});
						})
						res.data.forEach(item => {
							item.children.forEach(i => {
								array.push({
									pid: item.value,
									id: i.value,
									name: i.label
								})
								this.tlist.push({
									pid: item.value,
									id: i.value,
									name: i.label
								}); //3级分类
							})
						})
						this.tabtap(this.flist[0])
					} else {
						this.$api.msg(res.err)
					}
				})
				// let list = await this.$api.json('cateList');
				// list.forEach(item => {
				// 	if (!item.pid) {
				// 		this.flist.push(item); //pid为父级id, 没有pid或者pid=0是一级分类
				// 	} else if (!item.picture) {
				// 		this.slist.push(item); //没有图的是2级分类
				// 	} else {
				// 		this.tlist.push(item); //3级分类
				// 	}
				// })
			},
			//一级分类点击
			tabtap(item) {
				this.childList = this.slist.filter((i)=>i.pid === item.id)
				
				// if (!this.sizeCalcState) {
				// 	this.calcSize();
				// }

				this.currentId = item.id;
				// let index = this.slist.findIndex(sitem => sitem.pid === item.id);

				// this.tabScrollTop = this.slist[index].top;

				isUserTap = false // 点击左侧父级按钮后
			},
			//右侧栏滚动
			asideScroll(e) {
				if (!isUserTap) {
					// 非用户滑动
					return
				}

				if (!this.sizeCalcState) {
					this.calcSize();
				}
				let scrollTop = e.detail.scrollTop;
				let tabs = this.slist.filter(item => item.top <= scrollTop).reverse();
				if (tabs.length > 0) {
					this.currentId = tabs[0].pid;
				}
			},
			//
			tapScrollView() {
				// 用户触摸右侧后
				isUserTap = true
			},
			//计算右侧栏每个tab的高度等信息
			calcSize() {
				return
				let h = 0;
				this.slist.forEach(item => {
					let view = uni.createSelectorQuery().select("#main-" + item.id);
					view.fields({
						size: true
					}, data => {
						item.top = h;
						h += data.height;
						item.bottom = h;
					}).exec();
				})
				this.sizeCalcState = true;
			},
			navToList(titem) {
				this.$pageTo({
					url: `/pages/doctor/list`,
					options:{code:titem.id}
				})
			}
		}
	}
</script>

<style lang='scss'>
	page,
	.content {
		height: 100%;
		background-color: #f8f8f8;
	}

	.common-place {
		height: 40vh;
	}

	.content {
		display: flex;
	}

	.left-aside {
		flex-shrink: 0;
		width: 200upx;
		height: 100%;
		background-color: #fff;
	}

	.f-item {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100upx;
		font-size: 28upx;
		color: $font-color-base;
		position: relative;

		&.active {
			color: $base-color;
			background: #f8f8f8;

			&:before {
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				height: 36upx;
				width: 8upx;
				background-color: $base-color;
				border-radius: 0 4px 4px 0;
				opacity: .8;
			}
		}
	}

	.right-aside {
		flex: 1;
		overflow: hidden;
		padding-left: 20upx;
	}

	.s-item {
		display: flex;
		align-items: center;
		height: 70upx;
		padding-top: 8upx;
		font-size: 28upx;
		color: $font-color-dark;
	}

	.t-list {
		display: flex;
		/* flex-wrap: wrap; */
		flex-direction: column;
		width: 100%;
		background: #fff;
		padding-top: 12upx;

		&:after {
			content: '';
			flex: 99;
			height: 0;
		}
	}

	.t-item {
		flex-shrink: 0;
		display: flex;
		justify-content: flex-start;
		align-items: center;
		/* flex-direction: column; */
		width: 100%;
		font-size: 26upx;
		color: #666;
		/* padding-bottom: 20upx; */
		height: 60px;
		border-bottom: 1px solid #fafafa;
		padding-left: 20px;

		image {
			width: 140upx;
			height: 140upx;
		}
	}
</style>

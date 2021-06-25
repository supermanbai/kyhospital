<script>
	export default {
		onLaunch: function() {
			console.log('App Launch')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style>
	/*每个页面公共css */
	.uni-input-input {
		font-size: 15px;
	}
	/*css reset*/
	input,textarea,select,button{ 
	    font-family:arial;
	    font-family:inherit;
	}
	body,h1,h2,h3,h4,h5,h6,p,ul,ol,form{
	    margin:0;
	}
	h1,h2,h3,h4,h5,h6,input,select,textarea {
	    font-size: 100%
	}
	ul,ol{
	    padding-left:0; 
	    list-style-type:none;
	}

</style>

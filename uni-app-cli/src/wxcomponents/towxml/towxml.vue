<template>
<uni-shadow-root class="towxml-towxml"><view :class="'h2w h2w-'+(nodes.theme)">
	<view class="h2w__main">
		<decode :nodes="nodes"></decode>
	</view>
</view></uni-shadow-root>
</template>

<script>
import Decode from './decode.vue'
global['__wxVueOptions'] = {components:{'decode': Decode}}

global['__wxRoute'] = 'towxml/towxml'
Component({
  options:{
    styleIsolation:'shared'
  },
  properties:{
    nodes:{
      type:Object,
      value:{}
    }
  },
  data:{
    someData:{
      
    }
  }
})
export default global['__wxComponents']['towxml/towxml']
</script>
<style platform="mp-weixin">
/**基础风格样式**/
@import './style/main.css';

/**主题配色（浅色样式）**/
@import './style/theme/light.css';

/**主题配色（深色样式）**/
@import './style/theme/dark.css';
</style>
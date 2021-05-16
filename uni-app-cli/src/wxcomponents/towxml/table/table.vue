<template>
<uni-shadow-root class="towxml-table-table"><block v-if="data.tag === 'table'">
    <view class="h2w__tableParent">
        <view :class="data.attrs.class" :width="data.attrs.width" :style="data.attrs.style">
            
            <block v-for="(item,index) in (data.children)" :key="item.i" v-if="data.children">
                <view v-if="item.tag" :class="item.attrs.class">
                    
                    <block v-for="(item,index) in (item.children)" :key="item.i" v-if="item.children">
                        <view v-if="item.tag" :class="item.attrs.class">
                            
                            <block v-for="(item,index) in (item.children)" :key="item.i" v-if="item.children">
                                <view v-if="item.tag" :class="item.attrs.class" :width="data.attrs.width" :style="data.attrs.style">
                                    
                                    <decode v-if="item.children" :nodes="item"></decode>
                                </view>
                            </block>
                        </view>
                    </block>
                </view>
            </block>
        </view>
    </view>
</block></uni-shadow-root>
</template>

<script>
import Decode from '../decode.vue'
global['__wxVueOptions'] = {components:{'decode': Decode}}

global['__wxRoute'] = 'towxml/table/table'
Component({
	options: {
		styleIsolation: 'shared'
	},
	properties: {
		data: {
			type: Object,
			value: {}
		}
	}
})
export default global['__wxComponents']['towxml/table/table']
</script>
<style platform="mp-weixin">

</style>
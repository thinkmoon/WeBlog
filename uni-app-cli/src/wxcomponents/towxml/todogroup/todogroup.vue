<template>
<uni-shadow-root class="towxml-todogroup-todogroup"><checkbox-group v-if="data.tag === 'todogroup'" :class="data.attrs.class" @change="_change">
    <block v-for="(item,index) in (data.children)" :key="item.i" v-if="data.children">
        <label v-if="item.tag" :class="item.attrs.class">
            <block v-for="(item,index) in (item.children)" :key="item.i" v-if="item.children">
                
                <checkbox v-if="item.tag === 'checkbox'" :class="item.attrs.class" :value="item.attrs.value" :data-_e="item" :checked="item.attrs.checked" :disabled="item.attrs.disabled"></checkbox>
                
                <decode v-if="item.children" :nodes="item"></decode>
            </block>
        </label>
    </block>
</checkbox-group></uni-shadow-root>
</template>

<script>
import Decode from '../decode.vue'
global['__wxVueOptions'] = {components:{'decode': Decode}}

global['__wxRoute'] = 'towxml/todogroup/todogroup'
Component({
	options: {
		styleIsolation: 'shared'
	},
	properties: {
		data: {
			type: Object,
			value: {}
		}
	},
	data: {
	},
	methods: {
		_change:function(...arg){
			if(global._events && typeof global._events.change === 'function'){
				global._events.change(...arg);
			}
		}
	}
})
export default global['__wxComponents']['towxml/todogroup/todogroup']
</script>
<style platform="mp-weixin">
.h2w__todolist {
    display:block;
}
</style>
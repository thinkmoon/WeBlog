<template>
<uni-shadow-root class="towxml-decode"><block v-for="(item,i) in (nodes.children)" :key="item.i"><block v-if="item.tag===undefined">{{item.text}}</block><block v-if="item.tag==='view'"><view class="h2w__viewParent"><view :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></view></view></block><block v-if="item.tag==='video'"><view class="h2w__videoParent"><video :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" :poster="item.attrs.poster" :src="item.attrs.src"><decode v-if="item.children" :nodes="item"></decode></video></view></block><block v-if="item.tag==='text'"><view class="h2w__textParent"><text :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></text></view></block><block v-if="item.tag==='image'"><view class="h2w__imageParent"><image :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" :src="item.attrs.src" :mode="item.attrs.mode ? item.attrs.mode : 'widthFix'" :lazy-load="item.attr['lazy-load']"><decode v-if="item.children" :nodes="item"></decode></image></view></block><block v-if="item.tag==='navigator'"><view class="h2w__navigatorParent"><navigator :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" :url="item.attrs.href"><decode v-if="item.children" :nodes="item"></decode></navigator></view></block><block v-if="item.tag==='swiper'"><view class="h2w__swiperParent"><swiper :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></swiper></view></block><block v-if="item.tag==='swiper-item'"><view class="h2w__swiper-itemParent"><swiper-item :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></swiper-item></view></block><block v-if="item.tag==='block'"><view class="h2w__blockParent"><block :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.native.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></block></view></block><block v-if="item.tag==='form'"><view class="h2w__formParent"><form :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></form></view></block><block v-if="item.tag==='input'"><view class="h2w__inputParent"><input :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"></input><decode v-if="item.children" :nodes="item"></decode></view></block><block v-if="item.tag==='textarea'"><view class="h2w__textareaParent"><textarea :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></textarea></view></block><block v-if="item.tag==='button'"><view class="h2w__buttonParent"><button :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></button></view></block><block v-if="item.tag==='checkbox-group'"><view class="h2w__checkbox-groupParent"><checkbox-group :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" @change.stop.prevent="_change" @change="_$self[(item.attrs.bindchange)||'_$noop']($event)"><decode v-if="item.children" :nodes="item"></decode></checkbox-group></view></block><block v-if="item.tag==='checkbox'"><view class="h2w__checkboxParent"><checkbox :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" :checked="item.attrs.checked" :value="item.attrs.value"><decode v-if="item.children" :nodes="item"></decode></checkbox></view></block><block v-if="item.tag==='radio-group'"><view class="h2w__radio-groupParent"><radio-group :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap"><decode v-if="item.children" :nodes="item"></decode></radio-group></view></block><block v-if="item.tag==='radio'"><view class="h2w__radioParent"><radio :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :id="item.attrs.id" :style="item.attrs.style" @click.stop.prevent="_tap" :checked="item.attrs.checked"><decode v-if="item.children" :nodes="item"></decode></radio></view></block><block v-if="item.tag==='rich-text'"><view class="h2w__rich-textParent"><rich-text :data-data="item" :class="item.attrs.class" :data="item.attrs.data" :style="item.attrs.style" @click.stop.prevent="_tap" :nodes="item.children"><decode v-if="item.children" :nodes="item"></decode></rich-text></view></block><block v-if="item.tag==='audio-player'"><audio-player :data="item" :data-data="item" @click.native.stop.prevent="_tap"></audio-player></block><block v-if="item.tag==='latex'"><latex :data="item" :data-data="item" @click.native.stop.prevent="_tap"></latex></block><block v-if="item.tag==='table'"><table :data="item" :data-data="item" @click.native.stop.prevent="_tap"></table></block><block v-if="item.tag==='todogroup'"><todogroup :data="item" :data-data="item" @click.native.stop.prevent="_tap"></todogroup></block><block v-if="item.tag==='yuml'"><yuml :data="item" :data-data="item" @click.native.stop.prevent="_tap"></yuml></block><block v-if="item.tag==='img'"><img :data="item" :data-data="item" @click.native.stop.prevent="_tap"></img></block></block></uni-shadow-root>
</template>

<script>
import Decode from './decode.vue'
import AudioPlayer from './audio-player/audio-player.vue'
import Latex from './latex/latex.vue'
import Table from './table/table.vue'
import Todogroup from './todogroup/todogroup.vue'
import Yuml from './yuml/yuml.vue'
import Img from './img/img.vue'
global['__wxVueOptions'] = {components:{'decode': Decode,'audio-player': AudioPlayer,'latex': Latex,'table': Table,'todogroup': Todogroup,'yuml': Yuml,'img': Img}}

global['__wxRoute'] = 'towxml/decode'
const config = require('./config');

Component({
	options: {
		styleIsolation: 'apply-shared'
	},
	properties: {
		nodes: {
			type,
			value: {}
		}
	},
	lifetimes: {
		attached: function () {
			const _ts = this;

			config.events.forEach(item => {
				_ts['_' + item] = function (...arg) {
					if (global._events && typeof global._events[item] === 'function') {
						global._events[item](...arg);
					}
				};
			});
		}
	}
})
export default global['__wxComponents']['towxml/decode']
</script>
<style platform="mp-weixin">

</style>
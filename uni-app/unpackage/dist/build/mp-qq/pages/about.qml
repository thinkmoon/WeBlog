<view><view class="overView flex justify-between flex-direction" style="height:40vh;width:100%;"><view class="flex justify-between align-center" style="height:30vh;width:100%;"><image class="avatar shadow" src="https://www.thinkmoon.cn/usr/uploads/2018/12/55979974.jpg"></image></view><view class="flex align-end padding-xs justify-around text-white text-shadow text-bold bg-shadeBottom" style="width:100%;"><view class="margin-xs flex align-center flex-direction"><view>文章</view><view>{{Overview.posts[0].Num}}</view></view><view class="margin-xs flex align-center flex-direction"><view>留言</view><view>{{Overview.comments[0].Num}}</view></view><view class="margin-xs flex align-center flex-direction"><view>分类</view><view>{{Overview.categorys[0].Num}}</view></view><view class="margin-xs flex align-center flex-direction"><view>标签</view><view>{{Overview.tags[0].Num}}</view></view></view></view><block wx:if="{{postData[0].text}}"><towxml vue-id="1" content="{{postData[0].text}}" data-com-type="wx" bind:__l="__l"></towxml></block><block wx:if="{{isLoading}}"><view class="cu-load load-modal"><view class="icon-emojifill text-green"></view><view class="gray-text">加载中...</view></view></block><ad unit-id="01a084f529176b8df06deaa2274f6721" type="card"></ad><tm-footer vue-id="2" bind:__l="__l"></tm-footer></view>
import wepy from 'wepy'
export default class Page extends wepy.page {
  onShareAppMessage() {
    return {
      title: '指尖魔法屋'
    }
  }
}

import { GET } from "./http";
export default class Api {
  /**
   * 登录接口
   * @param params code
   */
  login(params) {
    // #ifdef MP-QQ
    return GET("login?mp=qq", params);
    // #endif
    // #ifdef MP-WEIXIN
    return GET("login?mp=weixin", params);
    // #endif
  }
  // 获取最近文章
  getPost(params) {
    return GET("post/list", params);
  }
  // 通过cid获取文章
  getPostBycid(params) {
    return GET(`post/${params.cid}`);
  }
  getSticky(params) {
    return GET("getSticky", params);
  }
  getAboutCid(params = {}) {
    return GET("post/about");
  }
  /**
   * 获取所有分类
   */
  getCategories() {
    return GET("category/list");
  }
  /**
   * 获取所有标签
   */
  getTags() {
    return GET("tag/list");
  }
  // 获取总览
  getOverview() {
    return GET("getOverview");
  }
  // 获取用户点赞信息
  getPostLikeStatus(params) {
    return GET("getPostLikeStatus", params);
  }
  // 获取文章评论
  getComment(params) {
    return GET("getComment", params);
  }
  // 获取作者信息
  getAuthorInfo(params) {
    return GET("getAuthorInfo", params);
  }
  // 为文章点赞
  likePost(params) {
    return GET("likePost", params);
  }
  /**
   * 获取文章点赞用户列表
   */
  getLikeUsers(params) {
    return GET("getLikeUsers", params);
  }
  /**
   * 发表评论
   */
  addComment(params) {
    return GET("addComment", params);
  }
  // 搜索内容
  search(params) {
    return GET("post/search", params);
  }
}

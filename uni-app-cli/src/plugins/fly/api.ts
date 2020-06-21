import { GET } from "./http";
export const API = {
  // 登录
  login(params: any): any {
    // #ifdef MP-QQ
    return GET("login?mp=qq", params);
    // #endif
    // #ifdef MP-WEIXIN
    return GET("login?mp=weixin", params);
    // #endif
  },
  // 获取最近文章
  getPost(params: any): any {
    return GET("post", params);
  },
  // 通过cid获取文章
  getPostBycid(params: any): any {
    return GET("getPostBycid", params);
  },
  getSticky(params: any): any {
    return GET("getSticky", params);
  },
  // 获取总览
  getOverview(params: any): any {
    return GET("getOverview", params);
  },
  // 获取用户点赞信息
  getPostLikeStatus(params: any): any {
    return GET("getPostLikeStatus", params);
  },
  // 获取文章评论
  getComment(params: any): any {
    return GET("getComment", params);
  },
  // 获取作者信息
  getAuthorInfo(params: any): any {
    return GET("getAuthorInfo", params);
  },
  // 为文章点赞
  likePost(params: any): any {
    return GET("likePost", params);
  },
  // 获取文章点赞用户列表
  getLikeUsers(params: any): any {
    return GET("getLikeUsers", params);
  },
  // 发表评论
  addComment(params: any): any {
    return GET("addComment", params);
  },
  // 搜索内容
  search(params: any): any {
    return GET("search", params);
  },
};

import { GET } from "./http";
export default class Api {
  /**
   * 登录接口
   * @param params code
   */
  login(params: object): any {
    // #ifdef MP-QQ
    return GET("login?mp=qq", params);
    // #endif
    // #ifdef MP-WEIXIN
    return GET("login?mp=weixin", params);
    // #endif
  }
  // 获取最近文章
  getPost(params: object): any {
    return GET("posts", params);
  }
  // 通过cid获取文章
  getPostBycid(params: object): any {
    return GET("getPostBycid", params);
  }
  getSticky(params: object): any {
    return GET("getSticky", params);
  }
  getAboutCid(params = {}): any {
    return GET("getAboutCid", params);
  }
  /**
   * 获取所有分类
   */
  getCategories(): any {
    return GET("categories");
  }
  /**
   * 获取所有标签
   */
  getTags(): any {
    return GET("tags");
  }
  // 获取总览
  getOverview(): any {
    return GET("getOverview");
  }
  // 获取用户点赞信息
  getPostLikeStatus(params: object): any {
    return GET("getPostLikeStatus", params);
  }
  // 获取文章评论
  getComment(params: object): any {
    return GET("getComment", params);
  }
  // 获取作者信息
  getAuthorInfo(params: object): any {
    return GET("getAuthorInfo", params);
  }
  // 为文章点赞
  likePost(params: object): any {
    return GET("likePost", params);
  }
  /**
   * 获取文章点赞用户列表
   */
  getLikeUsers(params: object): any {
    return GET("getLikeUsers", params);
  }
  /**
   * 发表评论
   */
  addComment(params: object): any {
    return GET("addComment", params);
  }
  // 搜索内容
  search(params: object): any {
    return GET("search", params);
  }
}

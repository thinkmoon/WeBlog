var Fly=require("flyio/dist/npm/wx") 
var fly=new Fly

fly.config.baseURL= process.env.NODE_ENV === 'production' ? "https://www.thinkmoon.cn/WeBlog/api/" : "http://127.0.0.1/WeBlog/api/"
fly.config.headers={
	"openid":uni.getStorageSync("openid")
}

fly.interceptors.response.use(
    (response) => {
        //只将请求结果的data字段返回
        return response.data.data
    },
    (err) => {
        //发生网络错误后会走到这里
        return Promise.resolve("ERROR")
    }
)

// 登录
export const login = (params) => {
	return fly.get('login', params)
}
// 获取最近文章
export const getRecentPost = (params) => {
	return fly.get('getRecentPost', params)
}
// 获取作者信息
export const getAuthorInfo = (params) => {
	return fly.get('getAuthorInfo', params)
}
// 通过cid获取文章
export const getPostBycid = (params) => {
	return fly.get('getPostBycid', params)
}
// 获取总页数
export const getPageNum = (params) => {
	return fly.get('getPageNum', params)
}
// 获取总览
export const getOverview = (params) => {
	return fly.get('getOverview', params)
}
// 获取用户点赞信息
export const getPostLikeStatus = (params) => {
	return fly.get('getPostLikeStatus', params)
}
// 为文章点赞
export const likePost = (params) => {
	return fly.get('likePost', params)
}
// 获取文章点赞用户列表
export const getLikeUsers = (params) => {
	return fly.get('getLikeUsers', params)
}
// 获取关于页cid
export const getAboutcid = (params) => {
	return fly.get('getAboutcid', params)
}
// 发表评论
export const addComment = (params) => {
	return fly.get('addComment', params)
}
// 获取文章评论
export const getComment = (params) => {
	return fly.get('getComment', params)
}





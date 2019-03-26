import { GET } from './http_Util'
// 获取最近文章
export const getRecentPost = (params) => {
  return GET('getRecentPost', params)
}
// 获取作者信息
export const getAuthorInfo = (params) => {
  return GET('getAuthorInfo', params)
}
// 通过cid获取文章
export const getPostBycid = (params) => {
  return GET('getPostBycid', params)
}

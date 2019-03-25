'use strict'

import wepy from 'wepy'
var baseURL = 'https://www.thinkmoon.cn/TmWeBlog/api/'
async function request(api, method, header, params) {
  return wepy.request({
    url: baseURL + api,
    method: method,
    header: header,
    data: params
  })
}

async function get(api, params, success) {
  var GET_METHOD = 'GET'
  var GET_HEADER = {}
  return await request(api, GET_METHOD, GET_HEADER, params)
}

async function post(api, params, success) {
  var POST_METHOD = 'POST'
  var POST_HEADER = {}
  request(api, POST_METHOD, POST_HEADER, params, success)
}
module.exports = {
  GET: get,
  POST: post
}

> Typecho插件WeBlog的restful API 设计文档

tips: 设计阶段，未投入使用

# Version v2.0

## URL

https://${baseUrl}/WeBlog/api/

## Status Codes

- 200 OK - [GET]：服务器成功返回用户请求的数据，该操作是幂等的（Idempotent）。
- 401 Unauthorized - [*]：表示用户没有权限（令牌、用户名、密码错误）。
- 403 Forbidden - [*] 表示用户得到授权（与401错误相对），但是访问是被禁止的。
- 404 NOT FOUND - [*]：用户发出的请求针对的是不存在的记录，服务器没有进行操作，该操作是幂等的。
- 500 INTERNAL SERVER ERROR - [*]：服务器发生错误，用户将无法判断发出的请求是否成功。

## GET

- GET	/posts/	Browse posts

response
```json
[
  {
    'thumb': '缩略图.png', // 支持，拼接
    'desc': '文章描述',
    'title': '文章标题',
    'tags': '文章标签', // 支持，拼接
    'category': '文章分类', 
    'authorName': '作者名',
    'createTime': '创建时间',
    'updateTime': '更新时间',
    'like': '点赞数',
    'comment': '评论数',
    'view': '阅读数',
  }
  ...
]
```

GET	/posts/{id}/	Read a post by ID

response
```json
[
  {
    text: '文章内容', // 支持，拼接
    desc: '文章描述',
    title: '文章标题',
    tags: '文章标签', // 支持，拼接
    category: '文章分类', 
    authorName: '作者名',
    createTime: '创建时间',
    updateTime: '更新时间',
    like: '点赞数',
    comment: '评论数',
    view: '阅读数',
  }
  ...
]
```

GET	/tags/	Browse tags
GET	/tags/{id}/	Read a tag by ID
GET	/categories/	Browse tags
GET	/categories/{id}/	Read a tag by category ID
GET	/pages/{id}/	Read a single page by page ID
GET /like/posts/{id} Return people list by post ID
GET /comments/post/{id} Return comment list by post ID

### filters

1. limit

Value: ^[1-9]\d*$

2. pageSize

Value: ^[1-9]\d*$

3. curPage

Value: ^[1-9]\d*$


## POST
- POST /login

... 

- POST /comments/ add a comment by comment ID

request
```json
{
  postId: 1,
  commentId: 50
}
```
- POST /like/ like a post

request
```json
{
  "postId": 1
}
```

## PUT

## DELETE



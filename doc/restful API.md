> Typecho插件WeBlog的restful API 设计文档

tips: 设计阶段，未投入使用

# Version v1.0

## URL

https://${baseUrl}/WeBlog/api/

## Status Codes

- 200 OK - [GET]：服务器成功返回用户请求的数据，该操作是幂等的（Idempotent）。
- 401 Unauthorized - [*]：表示用户没有权限（令牌、用户名、密码错误）。
- 403 Forbidden - [*] 表示用户得到授权（与401错误相对），但是访问是被禁止的。
- 404 NOT FOUND - [*]：用户发出的请求针对的是不存在的记录，服务器没有进行操作，该操作是幂等的。
- 500 INTERNAL SERVER ERROR - [*]：服务器发生错误，用户将无法判断发出的请求是否成功。

## GET

- GET	/post/	Browse posts

response
```json
[
  {
    "thumb": "缩略图.png",
    "desc": "文章描述",
    "title": "文章标题",
    "tags": "文章标签",
    "category": "文章分类", 
    "authorName": "作者名",
    "createTime": "创建时间",
    "updateTime": "更新时间",
    "like": "点赞数",
    "comment": "评论数",
    "view": "阅读数",
  }
]
```

- GET	/post/{id}/	Read a post by ID

文章列表引用[1]

response
```json
[
  {
    "text": "文章内容", 
    "desc": "文章描述",
    "title": "文章标题",
    "tags": "文章标签", 
    "category": "文章分类", 
    "authorName": "作者名",
    "createTime": "创建时间",
    "updateTime": "更新时间",
    "like": "点赞数",
    "comment": "评论数",
    "view": "阅读数",
  }
]
```

- GET	/tag/	Browse tags

response
```json
[
  {
    "name": "标签名",
    "id": "标签ID",
    "num": "标签文章数"
  }
]
```

- GET	/post/tag/{id}/	返回标签ID下文章列表

文章列表引用[2]

response
```json
[
  {
    "text": "文章内容", 
    "desc": "文章描述",
    "title": "文章标题",
    "tags": "文章标签", 
    "category": "文章分类", 
    "authorName": "作者名",
    "createTime": "创建时间",
    "updateTime": "更新时间",
    "like": "点赞数",
    "comment": "评论数",
    "view": "阅读数",
  }
]
```

- GET	/category/	返回所有分类

response
```json
[
  {
    "name": "分类名",
    "id": "分类ID",
    "num": "分类文章数"
  }
  ...
]
```

- GET	/post/category/{id}/	根据分类ID返回文章列表

文章列表引用[3]

response
```json
[
  {
    "text": "文章内容", 
    "desc": "文章描述",
    "title": "文章标题",
    "tags": "文章标签", 
    "category": "文章分类", 
    "authorName": "作者名",
    "createTime": "创建时间",
    "updateTime": "更新时间",
    "like": "点赞数",
    "comment": "评论数",
    "view": "阅读数",
  }
]
```

- GET	/page/{id}/	Read a single page by page ID

- GET /like/post/{id} Return people list by post ID
- GET /comment/post/{id} Return comment list by post ID

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
  "postId": 1,
  "commentId": 50,
  "content": "评论内容"
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



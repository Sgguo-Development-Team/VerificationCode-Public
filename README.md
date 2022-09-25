# VerificationCode-Public

**字体 / 数据库 / 其他内容 版权归原作者所有，仅为引用**

## 技术栈：

- MySQL
- PHP 7.4

## How to USE?

1. 首先你需要进行一个环境的装
2. 其次你需要导入根目录的 SQL
3. 然后你需要修改数据配置
4. 运行 ```composer install``` 安装所需包
5. 记得找一个大冤种图床或者自己建一个，否则会一直占用空间
6. 看下面


### 接口细节

请求内容：

```GET HTTP/1.1 https://example/verification/Handle.php?config=History```

理想的返回值：
```json
{
  "ID": "9889",
  "Type": "History",
  "Value": "https://public-img.mycdn.sgguo.com/2022/09/25/21/1664096215-633017d73fcf3.png",
  "Question": "请输入上图事件发生日期",
  "Note": "格式例如：11451400，公元前请加 '-'表示",
  "Answer": "8ebe69b8410442e39a3e3cc899608c09"
}
```

JavaScript 于客户端的实现:

```javascript
// 注：config 可改为 Chemical | 链接自己想
fetch(`${new URL(window.location)["origin"]}/path-to-php-file?config=History`, {
    method: "GET",
    mode: "no-cors", // no-cors, *cors, same-origin
    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
    credentials: "same-origin", // include, *same-origin, omit
    headers: {
        "Content-Type": "application/x-www-form-urlencoded",
    },
    referrerPolicy: "no-referrer",
})
    .then((response) => response.json())
    .then((data) => {
        // Your Code
    })
    .catch((e) => {
        throw e;
});
```

### Slogan

'Question' => '请输入上图物质的分子式'

**Powered by SDT.**

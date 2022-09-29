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
// 注：config 可改为 Chemical
"use strict";
function getVerification(url, method = 'GET', data = {}, err = e => { throw e; }) {
    method = method.toUpperCase();
    const requestHeader = {
        headers: {
            'content-type': 'application/json',
        },
        method
    };
    if (method === 'GET') {
        let esc = encodeURIComponent;
        let queryParams = Object.keys(data)
            .map(k => `${esc(k)}=${esc(data[k])}`)
            .join('&');
        if (queryParams)
            url += `?${queryParams}`;
    }
    else {
        requestHeader.body = JSON.stringify(data);
    }
    return fetch(url, requestHeader).then(
    // 注意自己修改错误处理机制
    response => response.json()).catch(err);
};

const response = getVerification('Your API', 'GET', { config: 'Something...' }, e => {console.error(e)}).then((res) => {
    // Your Code;
});
```

### Slogan

'Question' => '请输入上图物质的分子式'

### Something Else

   [![在线状态](https://img.shields.io/website?down_color=red&down_message=Offline&label=在线实例&style=for-the-badge&up_color=green&up_message=Online&url=https://service-api.sgguo.com/verification/Handle.php)](https://service-api.sgguo.com/verification/Handle.php)   [![License](https://img.shields.io/github/license/Sgguo-Development-Team/VerificationCode-Public?label=%E6%88%91%E4%BB%AC%E6%AD%A3%E5%9C%A8%E4%BD%BF%E7%94%A8&style=for-the-badge)](https://github.com/Sgguo-Development-Team/VerificationCode-Public/blob/main/LICENSE)   [![Github](https://img.shields.io/github/followers/Sgguo-Development-Team?label=%E6%AD%A3%E5%9C%A8%E5%85%B3%E6%B3%A8%E5%BC%80%E5%8F%91%E5%B0%8F%E7%BB%84&logo=github&style=for-the-badge)](https://github.com/Sgguo-Development-Team)
   
<p align="center">Powered by SDT & Gong_cx.</p>

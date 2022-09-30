# VerificationCode-Public

**字体 / 数据库 / 其他内容 版权归原作者所有，仅为引用**

## 技术栈：

- MySQL
- PHP 8+

## How to USE?

1. 首先你需要进行一个环境的装
2. 其次你需要导入根目录的 SQL
3. 然后你需要修改数据配置
4. 运行 ```composer install``` 安装所需包
5. 记得找一个大冤种图床或者自己建一个，否则会一直占用空间
6. 看下面


### API 实现细节

请求内容：

```GET HTTP/1.1 https://example/path-to-handle?config=History```

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

```typescript
// 使用前请进行实例化，传 baseURL:string，在使用时直接使用此方法：实例化变量.getVerification('History'); 或其他配置，注意返回值为 Fetch 构造的 Promise
class Verification {
  baseURL: string;
  constructor(baseURL: string) {
    this.baseURL = baseURL;
  }
  public throwError(e: object) {
    throw e;
  }
  public getVerification(config: string): any {
    return fetch(`${this.baseURL}/${config}`, {
      method: "GET",
      mode: "cors", // no-cors, *cors, same-origin
      cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
      credentials: "same-origin", // include, *same-origin, omit
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      redirect: "follow", // manual, *follow, error
    });
  }
}
const getVerificationCode = new Verification("https://example");

getVerificationCode
  .getVerification("History")
  .then((res: any) => {
    res.json();
  })
  .then((data: any) => {
    // Your Code
  })
  .catch((err: any) => {
    // 你的错误处理机制
  });

```

### 其他我想说的

本项目有极大的可拓展性，返回结果就是个 Array，所以不止于 API 和 客户端的实现，你也可以不使用云服务方案，在本地 caches 文件夹进行本地操作，操作细节可以自己看代码

### Slogan

'Question' => '请输入上图物质的分子式'

### Something Else

   [![在线状态](https://img.shields.io/website?down_color=red&down_message=Offline&label=在线实例&style=for-the-badge&up_color=green&up_message=Online&url=https://service-api.sgguo.com/verification/Handle.php)](https://service-api.sgguo.com/verification/Handle.php)   [![License](https://img.shields.io/github/license/Sgguo-Development-Team/VerificationCode-Public?label=%E6%88%91%E4%BB%AC%E6%AD%A3%E5%9C%A8%E4%BD%BF%E7%94%A8&style=for-the-badge)](https://github.com/Sgguo-Development-Team/VerificationCode-Public/blob/main/LICENSE)   [![Github](https://img.shields.io/github/followers/Sgguo-Development-Team?label=%E6%AD%A3%E5%9C%A8%E5%85%B3%E6%B3%A8%E5%BC%80%E5%8F%91%E5%B0%8F%E7%BB%84&logo=github&style=for-the-badge)](https://github.com/Sgguo-Development-Team)
   
<p align="center">Powered by SDT & Gong_cx.</p>

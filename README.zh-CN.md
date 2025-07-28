# hejunjie/utils

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

一个零碎但实用的 PHP 工具函数集合库。包含文件、字符串、数组、网络请求等常用函数的工具类集合，提升开发效率，适用于日常 PHP 项目辅助功能。

> 🌱 很多实现原理都不复杂，但总在项目里反复写，写多了心态有点崩。
> 干脆集中起来，不重复造轮子，省心省力。

**本项目已经经由 Zread 解析完成，如果需要快速了解项目，可以点击次数进行查看：[了解本项目](https://zread.ai/zxc7563598/php-utils)**

## 安装方式

使用 Composer 安装：

```bash
composer require hejunjie/utils
```

## 用途 & 初衷

这是一个典型的「开发者懒癌工具库」。

在日常项目中，你可能也经常遇到这些情况：

一个数组操作写了无数次；

- 文件大小格式化总要搜 StackOverflow；
- 想判断字符串是不是 JSON、是不是手机号、是不是邮箱，结果各种复制粘贴；
- 项目变了，原来的工具类又得重新封装一遍...

所以，我把这些经常用、常见又简单的小方法统一整理了一下，写成这个工具库。不求高级，不追花哨，目标就是：简单易用，解放双手。

## 当前支持的方法（更新中）

## 当前支持的方法列表

### 字符串操作

| 方法                             | 说明                             |
| :------------------------------- | :------------------------------- |
| Str::containsAny()               | 检查字符串中是否存在数组中的内容 |
| Str::padString()                 | 补充特定字符串，使其达到指定长度 |
| Str::replaceFirst()              | 替换字符串中第一次出现的搜索值   |
| Str::generateRandomString()      | 生成随机字符串                   |
| Str::getRandomSurname()          | 获取随机姓氏                     |
| Str::truncateString()            | 截断字符串                       |
| Str::maskString()                | 字符串掩码                       |
| Str::removeWhitespace()          | 移除字符串中的所有空白字符       |
| Str::stringEncrypt()             | 字符串加密(AES-128-CBC)          |
| Str::stringDecrypt()             | 字符串解密(AES-128-CBC)          |
| Str::formatDurationFromSeconds() | 根据秒数转换为可读性时间         |

### 数组操作

| 方法                           | 说明                             |
| :----------------------------- | :------------------------------- |
| Arr::arrayIntersect()          | 获取两个数组的交集               |
| Arr::sortByField()             | 根据二维数组中的指定字段排序     |
| Arr::removeDuplicatesByField() | 根据二维数组中指定字段去重       |
| Arr::groupByField()            | 根据二维数组中的指定字段进行分组 |
| Arr::csvToArray()              | 读取 CSV 文件并返回数组格式      |
| Arr::arrayToCsv()              | 数组转换为 CSV 格式的字符串      |
| Arr::xmlParse()                | xml 解析为数组                   |
| Arr::arrayToXml()              | 数组转换为 xml                   |

### 文件操作

| 方法                                     | 说明                        |
| :--------------------------------------- | :-------------------------- |
| FileUtils::readFile()                    | 读取文件内容                |
| FileUtils::writeToFile()                 | 将内容写入文件              |
| FileUtils::getFileExtension()            | 获取文件扩展名              |
| FileUtils::joinPaths()                   | 拼接多个路径                |
| FileUtils::getFileNameWithoutExtension() | 获取文件名（不带扩展名）    |
| FileUtils::fileDelete()                  | 删除文件或目录              |
| FileUtils::writeUniqueLinesToFile()      | 获取文件中的唯一行（去重）  |
| FileUtils::getCommonLinesFromFiles()     | 从多个文件中获取交集行      |
| FileUtils::extractColumnFromCsvFiles()   | 从多个 csv 文件中快速提取列 |

### 网络请求操作

| 方法                          | 说明                     |
| :---------------------------- | :----------------------- |
| HttpClient::sendGetRequest()  | 使用 cURL 发送 GET 请求  |
| HttpClient::sendPostRequest() | 使用 cURL 发送 POST 请求 |
| HttpClient::downloadFile() | 下载远程文件并保存到本地 |

### 图片操作

| 方法                        | 说明                                                   |
| :-------------------------- | :----------------------------------------------------- |
| Img::downloadImageFromUrl() | 从 URL 下载图片                                        |
| Img::imageToBase64()        | 将图片转换为 Base64 字符串                             |
| Img::base64ToImage()        | 将 Base64 字符串保存为图片                             |
| Img::compressImage()        | 压缩图片到指定大小（单位 KB），支持多种格式转换为 JPEG |
| Img::resizeImage()          | 调整图片分辨率，保持宽高比                             |

### 导出操作

| 方法                           | 说明          |
| :----------------------------- | :------------ |
| DataExporter::exportTxt()      | 导出 TXT      |
| DataExporter::exportMarkdown() | 导出 Markdown |
| DataExporter::exportCsv()      | 导出 CSV      |
| DataExporter::exportJson()     | 导出 JSON     |
| DataExporter::exportSql()      | 导出 SQL      |
| DataExporter::exportHtml()     | 导出 HTML     |
| DataExporter::exportXml()      | 导出 XML      |

## 🔧 更多工具包（可独立使用，也可统一安装）

本项目最初是从 [hejunjie/tools](https://github.com/zxc7563598/php-tools) 拆分而来，如果你想一次性安装所有功能组件，也可以使用统一包：

```bash
composer require hejunjie/tools
```

当然你也可以按需选择安装以下功能模块：

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - 一个零碎但实用的 PHP 工具函数集合库。包含文件、字符串、数组、网络请求等常用函数的工具类集合，提升开发效率，适用于日常 PHP 项目辅助功能。

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - 基于装饰器模式实现的多层缓存系统，支持内存、文件、本地与远程缓存组合，提升缓存命中率，简化缓存管理逻辑。

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - 定期更新，全国最新省市区划分数据，身份证号码解析地址，支持 Composer 安装与版本控制，适用于表单选项、数据校验、地址解析等场景。

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - 基于责任链模式的错误日志处理组件，支持多通道日志处理（如本地文件、远程 API、控制台输出），适用于复杂日志策略场景。

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - 基于国内号段规则的手机号码归属地查询库，支持运营商识别与地区定位，适用于注册验证、用户画像、数据归档等场景。

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - 收货地址智能解析工具，支持从非结构化文本中提取姓名、手机号、身份证号、省市区、详细地址等字段，适用于电商、物流、CRM 等系统。

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - 用于生成带签名和加密保护的URL链接的PHP工具包，适用于需要保护资源访问的场景

[hejunjie/google-authenticator](https://github.com/zxc7563598/php-google-authenticator) - 一个用于生成和验证时间基础一次性密码（TOTP）的 PHP 包，支持 Google Authenticator 及类似应用。功能包括密钥生成、二维码创建和 OTP 验证。

[hejunjie/simple-rule-engine](https://github.com/zxc7563598/php-simple-rule-engine) - 一个轻量、易用的 PHP 规则引擎，支持多条件组合、动态规则执行，适合业务规则判断、数据校验等场景。

👀 所有包都遵循「轻量实用、解放双手」的原则，能单独用，也能组合用，自由度高，欢迎 star 🌟 或提 issue。

---

该库后续将持续更新，添加更多实用功能。欢迎大家提供建议和反馈，我会根据大家的意见实现新的功能，共同提升开发效率。

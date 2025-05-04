# hejunjie/utils

<div align="center">
  <a href="./README.md">English</a>｜<a href="./README.zh-CN.md">简体中文</a>
  <hr width="50%"/>
</div>

A lightweight and practical PHP utility library that offers a collection of commonly used helper functions for files, strings, arrays, and HTTP requests—designed to streamline development and support everyday PHP projects.

> 🌱 Many of these implementations are conceptually simple, but rewriting them repeatedly across projects can become quite tedious.
> To make things easier, I gathered these utilities in one place to avoid reinventing the wheel — saving both time and effort.

## Installation

Install via Composer:

```bash
composer require hejunjie/utils
```

## Purpose & Motivation

This is a typical "Developer's Time-Saving Toolkit."
In everyday projects, you may often find yourself dealing with situations like:

Rewriting array operations over and over again;

- Searching StackOverflow every time you need to format file sizes;
- Copy-pasting checks to determine if a string is JSON, a phone number, or an email address;
- Having to refactor your utility classes whenever the project changes...

So, I’ve compiled these simple and frequently-used methods into this toolkit. It’s not about complexity or fancy features; the goal is to keep it simple, easy to use, and save you time and effort.

## Currently Supported Methods (Updating)

## List of Currently Supported Methods

### String Operations

| method                           | describe                                                     |
| :------------------------------- | :----------------------------------------------------------- |
| Str::containsAny()               | Check if a string contains any of the elements in an array   |
| Str::padString()                 | Pad a string to a specified length with a specific character |
| Str::replaceFirst()              | Replace the first occurrence of a search value in a string   |
| Str::generateRandomString()      | Generate a random string                                     |
| Str::getRandomSurname()          | Get a random surname                                         |
| Str::truncateString()            | Truncate a string                                            |
| Str::maskString()                | String masking                                               |
| Str::removeWhitespace()          | Remove all whitespace characters from a string               |
| Str::stringEncrypt()             | Encrypt a string (AES-128-CBC)                               |
| Str::stringDecrypt()             | Decrypt a string (AES-128-CBC)                               |
| Str::formatDurationFromSeconds() | Convert seconds to a human-readable time format              |

### Array Operations

| method                         | describe                                                    |
| :----------------------------- | :---------------------------------------------------------- |
| Arr::arrayIntersect()          | Get the intersection of two arrays                          |
| Arr::sortByField()             | Sort a 2D array by a specific field                         |
| Arr::removeDuplicatesByField() | Remove duplicates from a 2D array based on a specific field |
| Arr::groupByField()            | Group a 2D array by a specific field                        |
| Arr::csvToArray()              | Read a CSV file and return it as an array                   |
| Arr::arrayToCsv()              | Convert an array to a CSV formatted string                  |
| Arr::xmlParse()                | Parse XML into an array                                     |
| Arr::arrayToXml()              | Convert an array to XML                                     |

### File Operations

| method                                   | describe                                        |
| :--------------------------------------- | :---------------------------------------------- |
| FileUtils::readFile()                    | Read file contents                              |
| FileUtils::writeToFile()                 | Write content to a file                         |
| FileUtils::getFileExtension()            | Get the file extension                          |
| FileUtils::joinPaths()                   | Join multiple paths together                    |
| FileUtils::getFileNameWithoutExtension() | Get the file name (without extension)           |
| FileUtils::fileDelete()                  | Delete a file or directory                      |
| FileUtils::writeUniqueLinesToFile()      | Get unique lines from a file (deduplication)    |
| FileUtils::getCommonLinesFromFiles()     | Get intersecting lines from multiple files      |
| FileUtils::extractColumnFromCsvFiles()   | Quickly extract columns from multiple CSV files |

### Network Request Operations

| method                        | describe                       |
| :---------------------------- | :----------------------------- |
| HttpClient::sendGetRequest()  | Send a GET request using cURL  |
| HttpClient::sendPostRequest() | Send a POST request using cURL |

### Image Operations

| method                      | describe                                                                                           |
| :-------------------------- | :------------------------------------------------------------------------------------------------- |
| Img::downloadImageFromUrl() | Download an image from a URL                                                                       |
| Img::imageToBase64()        | Convert an image to a Base64 string                                                                |
| Img::base64ToImage()        | Save a Base64 string as an image                                                                   |
| Img::compressImage()        | Compress an image to a specified size (in KB), with support for converting various formats to JPEG |
| Img::resizeImage()          | Resize an image while maintaining aspect ratio                                                     |

### Export Operations

| method                         | describe        |
| :----------------------------- | :-------------- |
| DataExporter::exportTxt()      | Export TXT      |
| DataExporter::exportMarkdown() | Export Markdown |
| DataExporter::exportCsv()      | Export CSV      |
| DataExporter::exportJson()     | Export JSON     |
| DataExporter::exportSql()      | Export SQL      |
| DataExporter::exportHtml()     | Export HTML     |
| DataExporter::exportXml()      | Export XML      |

## 🔧 Additional Toolkits (Can be used independently or installed together)

This project was originally extracted from [hejunjie/tools](https://github.com/zxc7563598/php-tools).
To install all features in one go, feel free to use the all-in-one package:

```bash
composer require hejunjie/tools
```

Alternatively, feel free to install only the modules you need：

[hejunjie/utils](https://github.com/zxc7563598/php-utils) - A lightweight and practical PHP utility library that offers a collection of commonly used helper functions for files, strings, arrays, and HTTP requests—designed to streamline development and support everyday PHP projects.

[hejunjie/cache](https://github.com/zxc7563598/php-cache) - A layered caching system built with the decorator pattern. Supports combining memory, file, local, and remote caches to improve hit rates and simplify cache logic.

[hejunjie/china-division](https://github.com/zxc7563598/php-china-division) - Regularly updated dataset of China's administrative divisions with ID-card address parsing. Distributed via Composer and versioned for use in forms, validation, and address-related features

[hejunjie/error-log](https://github.com/zxc7563598/php-error-log) - An error logging component using the Chain of Responsibility pattern. Supports multiple output channels like local files, remote APIs, and console logs—ideal for flexible and scalable logging strategies.

[hejunjie/mobile-locator](https://github.com/zxc7563598/php-mobile-locator) - A mobile number lookup library based on Chinese carrier rules. Identifies carriers and regions, suitable for registration checks, user profiling, and data archiving.

[hejunjie/address-parser](https://github.com/zxc7563598/php-address-parser) - An intelligent address parser that extracts name, phone number, ID number, region, and detailed address from unstructured text—perfect for e-commerce, logistics, and CRM systems.

[hejunjie/url-signer](https://github.com/zxc7563598/php-url-signer) - A PHP library for generating URLs with encryption and signature protection—useful for secure resource access and tamper-proof links.

[hejunjie/google-authenticator](https://github.com/zxc7563598/php-google-authenticator) - A PHP library for generating and verifying Time-Based One-Time Passwords (TOTP). Compatible with Google Authenticator and similar apps, with features like secret generation, QR code creation, and OTP verification.

[hejunjie/simple-rule-engine](https://github.com/zxc7563598/php-simple-rule-engine) - A lightweight and flexible PHP rule engine supporting complex conditions and dynamic rule execution—ideal for business logic evaluation and data validation.

👀 All packages follow the principles of being lightweight and practical — designed to save you time and effort. They can be used individually or combined flexibly. Feel free to ⭐ star the project or open an issue anytime!

---

This library will continue to be updated with more practical features. Suggestions and feedback are always welcome — I’ll prioritize new functionality based on community input to help improve development efficiency together.

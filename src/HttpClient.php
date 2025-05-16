<?php

namespace Hejunjie\Utils;

/**
 * 网络处理类
 * 
 * @package Hejunjie\Utils
 */
class HttpClient
{
    /**
     * 使用 cURL 发送 GET 请求
     * 
     * @param string $url URL地址
     * @param array $headers header数组
     * @param int $timeout 超时时间（秒）
     * 
     * @return array ['httpStatus' => 'Http Status 状态码', 'data' => '返回内容']
     * @throws Exception 如果请求失败抛出异常
     */
    public static function sendGetRequest(string $url, array $headers = [], int $timeout = 10): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('无效的 URL: ' . $url);
        }
        $ch = curl_init();
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 设置请求头
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        // 获取 HTTP 状态码
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // 检查 cURL 是否发生错误
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            throw new \Exception('cURL 错误: ' . $errorMsg);
        }
        curl_close($ch);
        // 返回结构化结果
        return [
            'httpStatus' => $httpStatus,
            'data' => $response
        ];
    }

    /**
     * 使用 cURL 发送 POST 请求
     * 
     * @param string $url URL地址
     * @param array $headers header数组
     * @param mixed $data 请求数据
     * @param int $timeout 超时时间（秒）
     * 
     * @return array ['httpStatus' => 'Http Status 状态码', 'data' => '返回内容']
     * @throws Exception 如果请求失败抛出异常
     */
    public static function sendPostRequest(string $url, array $headers = [], mixed $data = null, int $timeout = 10): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('无效的 URL: ' . $url);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // 设置请求头
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // 设置请求数据
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); // 获取 HTTP 状态码
        // 检查 cURL 是否发生错误
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            throw new \Exception('cURL 错误: ' . $errorMsg);
        }
        curl_close($ch);
        // 返回结构化结果
        return [
            'httpStatus' => $httpStatus,
            'data' => $response
        ];
    }

    /**
     * 下载远程文件并保存到本地
     *
     * @param string $url 资源链接
     * @param string $savePath 本地存储目录
     * @param string|null $filename 自定义文件名（可选）
     * 
     * @return string 返回保存后的绝对路径
     * @throws Exception  下载或保存失败时抛出异常
     */
    public static function downloadFile(string $url, string $savePath, ?string $filename = null): string
    {
        // 尝试获取文件内容
        $content = @file_get_contents($url);
        if ($content === false) {
            throw new \Exception("无法下载资源：$url");
        }

        // 确保保存路径存在
        if (!is_dir($savePath)) {
            if (!mkdir($savePath, 0777, true) && !is_dir($savePath)) {
                throw new \Exception("无法创建目录：$savePath");
            }
        }

        // 提取原始文件后缀
        $urlPath = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($urlPath, PATHINFO_EXTENSION);
        $extension = $extension ? ".$extension" : '';

        // 生成文件名
        if (!$filename) {
            $filename = uniqid('file_', true) . $extension;
        } elseif (!str_ends_with($filename, $extension) && $extension) {
            $filename .= $extension;
        }

        // 组合完整保存路径
        $fullPath = rtrim($savePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        // 保存文件
        if (file_put_contents($fullPath, $content) === false) {
            throw new \Exception("无法保存文件到：$fullPath");
        }

        return realpath($fullPath) ?: $fullPath;
    }
}

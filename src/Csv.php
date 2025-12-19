<?php

namespace Hejunjie\Csv;

use Exception;

/**
 * CSV处理类
 * 
 * @package Hejunjie\Csv
 */
class Csv
{

    /**
     * 读取 CSV 文件并转换为数组
     *
     * @param string $csvPath CSV 文件路径
     * @param bool   $useHeaderAsKey 是否使用第一行作为 key
     * @param string $delimiter CSV 分隔符，默认逗号
     * 
     * @return array
     * @throws Exception
     */
    function readCsvToArray(string $csvPath, bool $useHeaderAsKey = true, string $delimiter = ','): array
    {
        if (!is_readable($csvPath)) {
            throw new \Exception("CSV 文件不可读: {$csvPath}");
        }
        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            throw new \Exception("无法打开 CSV 文件: {$csvPath}");
        }
        $result = [];
        $header = [];
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($useHeaderAsKey && empty($header)) {
                $header = $row;
                continue;
            }
            if ($useHeaderAsKey) {
                $item = [];
                foreach ($header as $index => $key) {
                    $item[$key] = $row[$index] ?? null;
                }
                $result[] = $item;
            } else {
                $result[] = $row;
            }
        }
        fclose($handle);
        return $result;
    }

    /**
     * 将数组写入 CSV 文件
     *
     * @param array  $data 要写入的数据
     * @param string $filePath CSV 文件保存路径
     * @param bool   $writeHeader 是否写入表头（仅在二维关联数组时有效）
     * @param string $delimiter CSV 分隔符，默认逗号
     * 
     * @return void
     * @throws Exception
     */
    function arrayToCsv(array $data, string $filePath, bool $writeHeader = true, string $delimiter = ','): void
    {
        if (empty($data)) {
            throw new \Exception('写入 CSV 的数据为空');
        }
        // 确保目录存在
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new \Exception("无法创建目录: {$dir}");
            }
        }
        $handle = fopen($filePath, 'w');
        if ($handle === false) {
            throw new \Exception("无法写入 CSV 文件: {$filePath}");
        }
        fwrite($handle, "\xEF\xBB\xBF");
        $firstRow = reset($data);
        $isAssoc = array_keys($firstRow) !== range(0, count($firstRow) - 1);
        if ($writeHeader && $isAssoc) {
            fputcsv($handle, array_keys($firstRow), $delimiter);
        }
        // 写数据行
        foreach ($data as $row) {
            if ($isAssoc) {
                fputcsv($handle, array_values($row), $delimiter);
            } else {
                fputcsv($handle, $row, $delimiter);
            }
        }
        fclose($handle);
    }
}

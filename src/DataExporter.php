<?php

namespace Hejunjie\Utils;

use SimpleXMLElement;

/**
 * 数据导出类
 * 
 * @package Hejunjie\Utils
 */
class DataExporter
{
    /**
     * 导出 TXT
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportTxt($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.txt';
        $content = "";
        // 获取表头
        $columns = array_keys($data[0]);
        // 写入表头
        $content .= implode("\t", $columns) . "\n";
        // 写入数据
        foreach ($data as $row) {
            $content .= implode("\t", $row) . "\n"; // 每列用制表符隔开，每行换行
        }
        file_put_contents($filePath, $content);
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 Markdown
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportMarkdown($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.md';
        $markdown = "# 导出数据\n\n";
        // 获取表头
        $columns = array_keys($data[0]);
        // 添加表格头部
        $markdown .= "| " . implode(" | ", $columns) . " |\n";
        $markdown .= "| " . str_repeat(" --- |", count($columns)) . "\n"; // 表格分隔线
        // 添加表格数据
        foreach ($data as $row) {
            $markdown .= "| " . implode(" | ", $row) . " |\n";
        }
        file_put_contents($filePath, $markdown);
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 CSV
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportCsv($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.csv';
        $output = fopen($filePath, 'w');
        // 获取表头
        $columns = array_keys($data[0]);
        // 写入表头
        fputcsv($output, $columns);
        // 写入数据
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 JSON
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportJson($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.json';
        file_put_contents($filePath, json_encode($data, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES + JSON_PRESERVE_ZERO_FRACTION + JSON_PRETTY_PRINT));
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 SQL
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $tableName 数据库表名
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportSql($data, $tableName = 'data', $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.sql';
        $sql = "INSERT INTO `$tableName` (" . implode(", ", array_keys($data[0])) . ") VALUES\n";
        foreach ($data as $row) {
            $sql .= "(" . implode(", ", array_map(function ($value) {
                return "'" . addslashes($value) . "'"; // 防止注入，添加转义
            }, $row)) . "),\n";
        }
        $sql = rtrim($sql, ",\n") . ";"; // 去除最后的逗号
        file_put_contents($filePath, $sql);
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 HTML
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportHtml($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.html';
        $html = "<table border='1'><thead><tr>";
        // 获取表头
        $columns = array_keys($data[0]);
        foreach ($columns as $column) {
            $html .= "<th>$column</th>";
        }
        $html .= "</tr></thead><tbody>";
        // 写入数据
        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $value) {
                $html .= "<td>$value</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";
        file_put_contents($filePath, $html);
        // 返回文件路径
        return $filePath;
    }

    /**
     * 导出 XML
     * 
     * @param mixed $data 数据 [ [ 'key1' => 'value1', 'key2' => 'value2' ], [ 'key1' => 'value1', 'key2' => 'value2' ] ]
     * @param string $filename 文件名称
     * @param string $savePath 导出路径
     * 
     * @return string 
     */
    public static function exportXml($data, $filename = 'export', $savePath = '/tmp/')
    {
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filePath = $savePath . $filename . '.xml';
        // 创建XML对象
        $xml = new SimpleXMLElement('<root/>');
        // 循环数据并添加到XML中
        foreach ($data as $row) {
            $rowElement = $xml->addChild('row');
            foreach ($row as $key => $value) {
                $rowElement->addChild($key, htmlspecialchars($value)); // 使用 htmlspecialchars 处理特殊字符
            }
        }
        // 保存XML内容到文件
        $xml->asXML($filePath);
        // 返回文件路径
        return $filePath;
    }
}

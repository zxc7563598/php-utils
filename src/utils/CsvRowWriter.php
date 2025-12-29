<?php

namespace Hejunjie\utils\CsvRowWriter;

use Exception;

/**
 * CSV 单文件逐行写入器
 *
 * 用于在内存占用可控的前提下，将数据逐行追加写入 CSV 文件。
 * 适用于大数据量场景（如逐行读取 Excel / 数据库结果集后写入 CSV）。
 *
 * 特性说明：
 * - 以「追加模式（a）」打开文件，支持多次写入不中断
 * - 当文件为空时自动写入 UTF-8 BOM，保证 Excel 兼容性
 * - 支持关联数组自动写入表头（仅写一次）
 * - 同时支持索引数组与关联数组写入
 * - 本类仅负责“单个 CSV 文件”的写入
 * - 不负责文件句柄复用或并发控制
 * - 多文件 / 大规模写入场景应由上层 Pool 或管理器统一调度
 */
class CsvRowWriter
{
    // CSV 文件句柄
    private $handle;
    // 是否已写入表头
    private bool $headerWritten = false;
    // 是否需要写入表头
    private bool $writeHeader;
    // CSV 字段分隔符
    private string $delimiter;

    /**
     * 构造函数
     *
     * @param string $filePath CSV 文件路径
     * @param bool $writeHeader 是否在首次写入时自动写入表头
     * @param string $delimiter CSV 分隔符，默认英文逗号
     *
     * @throws Exception 当文件无法创建或无法写入时抛出异常
     */
    public function __construct(string $filePath, bool $writeHeader = true, string $delimiter = ',')
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->handle = fopen($filePath, 'a');
        if ($this->handle === false) {
            throw new \Exception("无法写入 CSV 文件: {$filePath}");
        }
        if (filesize($filePath) === 0) {
            fwrite($this->handle, "\xEF\xBB\xBF");
        }
        $this->writeHeader = $writeHeader;
        $this->delimiter = $delimiter;
    }

    /**
     * 写入一行数据到 CSV 文件
     *
     * @param array $row 单行数据（支持索引数组与关联数组）
     *
     * @return void
     */
    public function write(array $row): void
    {
        $isAssoc = array_keys($row) !== range(0, count($row) - 1);
        if ($this->writeHeader && $isAssoc && !$this->headerWritten) {
            fputcsv($this->handle, array_keys($row), $this->delimiter);
            $this->headerWritten = true;
        }
        fputcsv(
            $this->handle,
            $isAssoc ? array_values($row) : $row,
            $this->delimiter
        );
    }

    /**
     * 关闭 CSV 文件句柄
     *
     * 调用后当前写入器不应再被使用。
     * 通常由上层管理器（如 Pool）统一调度调用。
     *
     * @return void
     */
    public function close(): void
    {
        fclose($this->handle);
    }
}

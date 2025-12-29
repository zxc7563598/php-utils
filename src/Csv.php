<?php

namespace Hejunjie\Csv;

use Exception;
use Hejunjie\utils\CsvWriterPool\CsvWriterPool;

/**
 * CSV处理类
 * 
 * @package Hejunjie\Csv
 */
class Csv
{
    private static ?CsvWriterPool $pool = null;

    /**
     * 读取 CSV 文件并转换为数组
     *
     * @param string $csvPath CSV文件路径
     * @param bool $useHeaderAsKey 是否使用第一行作为 key
     * @param string $delimiter CSV 分隔符，默认逗号
     * 
     * @return array
     * @throws Exception
     */
    public static function readCsvToArray(string $csvPath, bool $useHeaderAsKey = true, string $delimiter = ','): array
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
     * @param array $data 要写入的数据
     * @param string $filePath CSV 文件保存路径
     * @param bool $writeHeader 是否写入表头（仅在二维关联数组时有效）
     * @param string $delimiter CSV 分隔符，默认逗号
     * 
     * @return void
     * @throws Exception
     */
    public static function arrayToCsv(array $data, string $filePath, bool $writeHeader = true, string $delimiter = ','): void
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

    /**
     * 逐行读取 CSV，并对每一行执行回调
     *
     * @param string $csvPath CSV文件路径
     * @param callable $onRow function(array $row, int $rowIndex): void
     * @param bool $useHeaderAsKey 是否使用第一行作为 key
     * @param string $delimiter CSV 分隔符，默认逗号
     *
     * @throws Exception
     */
    public static function readCsvEachRow(string $csvPath, callable $onRow, bool $useHeaderAsKey = true, string $delimiter = ','): void
    {
        if (!is_readable($csvPath)) {
            throw new \Exception("CSV 文件不可读: {$csvPath}");
        }
        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            throw new \Exception("无法打开 CSV 文件: {$csvPath}");
        }
        $header = [];
        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($useHeaderAsKey && empty($header)) {
                $header = $row;
                continue;
            }
            if ($useHeaderAsKey) {
                $assoc = [];
                foreach ($header as $i => $key) {
                    $assoc[$key] = $row[$i] ?? null;
                }
                $onRow($assoc, $rowIndex);
            } else {
                $onRow($row, $rowIndex);
            }
            $rowIndex++;
        }
        fclose($handle);
    }

    /**
     * 逐行写入 CSV
     * 
     * 本方法用于「逐行读取 → 按条件写入多个 CSV 文件」的场景。
     * 在大数据量（如百万行 Excel）处理时，可能会同时向大量不同文件写入数据。
     * 为避免一次性打开过多文件句柄导致系统资源耗尽，内部使用了文件句柄池（CsvWriterPool），并限制最大同时打开文件数。
     * 
     * 当所有写入操作完成后，强烈建议显式调用：csvRowWriterFinish()
     * 以主动关闭所有已打开的 CSV 文件句柄、及时释放系统资源。
     * 
     * @param array $row 要写入的数据（单行）
     * @param string $filePath CSV 文件保存路径
     * @param bool $useHeaderAsKey 是否使用第一行作为 key
     * @param string $delimiter CSV 分隔符，默认逗号
     * @param int $maxOpenFiles 最大同时打开文件数
     * 
     * @return void 
     */
    public static function csvRowWriter(array $row, string $filePath, bool $useHeaderAsKey = true, string $delimiter = ',', int $maxOpenFiles = 120): void
    {
        if (self::$pool === null) {
            self::$pool = new CsvWriterPool($maxOpenFiles, $useHeaderAsKey, $delimiter);
            register_shutdown_function([self::class, 'csvRowWriterFinish']);
        }
        self::$pool->write($filePath, $row);
    }

    /**
     * 逐行写入 CSV：关闭所有 CSV 文件句柄
     * 
     * @return void 
     */
    public static function csvRowWriterFinish(): void
    {
        if (self::$pool !== null) {
            self::$pool->closeAll();
            self::$pool = null;
        }
    }
}

<?php

namespace Hejunjie\utils\CsvWriterPool;

use Hejunjie\utils\CsvRowWriter\CsvRowWriter;

/**
 * CSV 写入器池（CsvRowWriter Pool）
 *
 * 用于管理多个 CSV 文件的逐行写入，解决以下问题：
 *
 * - 大数据量逐行处理时，可能同时向「大量不同 CSV 文件」写入数据
 * - 若每个文件都长期保持打开状态，容易触及系统文件句柄上限
 * - 若每次写入都打开 / 关闭文件，则性能开销过大
 *
 * 该类通过「有限数量的 CsvRowWriter + LRU 淘汰策略」：
 * - 控制同时打开的文件句柄数量
 * - 在性能与系统资源之间取得平衡
 *
 * 典型使用场景：
 * - 逐行读取大型 CSV / Excel / 数据库结果集
 * - 根据某些规则（如地区、类型、状态等）分流写入不同 CSV 文件
 *
 * 注意：
 * - 写入完成后务必调用 closeAll() 主动释放文件句柄
 * - 或由外部注册 shutdown handler 兜底释放
 */
class CsvWriterPool
{
    // 允许同时打开的最大 CSV 文件数
    private int $maxOpenFiles;
    // 当前已打开的 CSV 写入器
    private array $writers = [];
    // 文件访问顺序队列（LRU）
    private array $accessQueue = [];
    // 是否在 CSV 中自动写入表头
    private bool $writeHeader;
    // CSV 字段分隔符
    private string $delimiter;

    /**
     * 构造函数
     *
     * @param int $maxOpenFiles 同时允许打开的最大 CSV 文件数
     * @param bool $writeHeader 是否自动写入表头
     * @param string $delimiter CSV 分隔符，默认英文逗号
     */
    public function __construct(int $maxOpenFiles = 120, bool $writeHeader = true, string $delimiter = ',')
    {
        $this->maxOpenFiles = $maxOpenFiles;
        $this->writeHeader = $writeHeader;
        $this->delimiter = $delimiter;
    }

    /**
     * 向指定 CSV 文件写入一行数据
     *
     * 该方法对调用方是“无状态”的：
     * - 调用方无需关心文件是否已打开
     * - 无需手动管理 CsvRowWriter 实例
     *
     * 内部流程：
     * 1. 根据 filePath 获取或创建 CsvRowWriter
     * 2. 必要时触发 LRU 淘汰
     * 3. 将数据行写入目标 CSV
     *
     * @param string $filePath 目标 CSV 文件路径
     * @param array $row 单行数据
     *
     * @return void
     */
    public function write(string $filePath, array $row): void
    {
        $writer = $this->getWriter($filePath);
        $writer->write($row);
    }

    /**
     * 获取指定文件路径对应的 CsvRowWriter
     *
     * - 若已存在，则更新其访问顺序（LRU touch）
     * - 若不存在，则创建新的写入器
     * - 若已达到最大文件数限制，则先淘汰最久未使用的写入器
     *
     * @param string $filePath CSV 文件路径
     *
     * @return CsvRowWriter
     */
    private function getWriter(string $filePath): CsvRowWriter
    {
        if (isset($this->writers[$filePath])) {
            $this->touch($filePath);
            return $this->writers[$filePath];
        }
        if (count($this->writers) >= $this->maxOpenFiles) {
            $this->evict();
        }
        $writer = new CsvRowWriter(
            $filePath,
            $this->writeHeader,
            $this->delimiter
        );
        $this->writers[$filePath] = $writer;
        $this->accessQueue[] = $filePath;
        return $writer;
    }

    /**
     * 更新指定文件的访问顺序（LRU touch）
     *
     * 将 filePath 移动到访问队列末尾，
     * 表示“最近被使用”。
     *
     * @param string $filePath CSV 文件路径
     *
     * @return void
     */
    private function touch(string $filePath): void
    {
        $this->accessQueue = array_values(
            array_diff($this->accessQueue, [$filePath])
        );
        $this->accessQueue[] = $filePath;
    }

    /**
     * 淘汰最久未使用的 CsvRowWriter
     *
     * 关闭对应 CSV 文件句柄，并将其从池中移除。
     *
     * @return void
     */
    private function evict(): void
    {
        $oldest = array_shift($this->accessQueue);
        if ($oldest !== null && isset($this->writers[$oldest])) {
            $this->writers[$oldest]->close();
            unset($this->writers[$oldest]);
        }
    }

    /**
     * 关闭所有已打开的 CSV 文件句柄
     *
     * 强烈建议在所有写入完成后显式调用，以确保：
     * - 文件句柄及时释放
     * - 数据完整落盘
     *
     * @return void
     */
    public function closeAll(): void
    {
        foreach ($this->writers as $writer) {
            $writer->close();
        }
        $this->writers = [];
        $this->accessQueue = [];
    }
}

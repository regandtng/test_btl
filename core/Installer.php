<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Installer — tự động cấu hình cho lần chạy đầu tiên.
 *
 * Mục tiêu: sau khi `git clone` sang máy mới, ứng dụng tự tạo file `.env`
 * (nếu thiếu) và tự import `database/schema.sql` mà không cần thao tác thủ công
 * (miễn là thông tin MySQL dùng mặc định của XAMPP: root / mật khẩu rỗng).
 *
 * Có thể tắt bằng cách đặt AUTO_INSTALL=false trong `.env`.
 */
class Installer
{
    /**
     * Tạo `.env` từ `.env.example` nếu chưa tồn tại.
     */
    public static function ensureEnvFile(string $root): void
    {
        $env = $root . '/.env';
        $example = $root . '/.env.example';

        if (!file_exists($env) && is_readable($example)) {
            @copy($example, $env);
        }
    }

    /**
     * Tự tạo database + import schema nếu chưa cài đặt.
     * Bỏ qua nếu đã cài (có file lock `.installed`) hoặc AUTO_INSTALL=false.
     */
    public static function install(string $root): void
    {
        if (strtolower((string) ($_ENV['AUTO_INSTALL'] ?? 'true')) === 'false') {
            return;
        }

        $lock = $root . '/.installed';
        if (file_exists($lock)) {
            return;
        }

        $schema = $root . '/database/schema.sql';
        if (!is_readable($schema)) {
            return;
        }

        $config = require $root . '/config/database.php';

        // Kết nối tới server MySQL (chưa cần database tồn tại).
        $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Nếu database đã có sẵn bảng users => coi như đã cài đặt.
        if (self::databaseReady($pdo, $config['database'])) {
            @touch($lock);
            return;
        }

        // Tạo database theo đúng tên cấu hình trong .env rồi chọn nó.
        $database = str_replace('`', '', $config['database']);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$database}`");

        // Import toàn bộ schema (đã được viết thuần SQL, không dùng DELIMITER).
        $sql = file_get_contents($schema);
        foreach (self::splitSql($sql) as $statement) {
            $pdo->exec($statement);
        }

        @touch($lock);
    }

    private static function databaseReady(PDO $pdo, string $database): bool
    {
        try {
            $pdo->exec("USE `{$database}`");
            $pdo->query('SELECT 1 FROM users LIMIT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Tách file .sql thành từng câu lệnh, an toàn với chuỗi và comment.
     *
     * @return string[]
     */
    private static function splitSql(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inBacktick = false;
        $inLineComment = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $next = $i + 1 < $length ? $sql[$i + 1] : '';

            if ($inLineComment) {
                if ($char === "\n") {
                    $inLineComment = false;
                }
                continue;
            }

            // Comment dạng `-- ...` hoặc `# ...`
            if (!$inSingle && !$inBacktick && (($char === '-' && $next === '-') || $char === '#')) {
                $inLineComment = true;
                if ($char === '-') {
                    $i++;
                }
                continue;
            }

            // Ký tự escape trong chuỗi
            if ($inSingle && $char === '\\') {
                $buffer .= $char . $next;
                $i++;
                continue;
            }

            if ($char === "'" && !$inBacktick) {
                $inSingle = !$inSingle;
                $buffer .= $char;
                continue;
            }

            if ($char === '`' && !$inSingle) {
                $inBacktick = !$inBacktick;
                $buffer .= $char;
                continue;
            }

            if ($char === ';' && !$inSingle && !$inBacktick) {
                $statement = trim($buffer);
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $statement = trim($buffer);
        if ($statement !== '') {
            $statements[] = $statement;
        }

        return $statements;
    }
}

<?php

namespace Deceitya\MiningLevel\Database;

use Deceitya\MiningLevel\MiningLevelSystem;

class SQLiteDatabase
{
    private $db;

    public function __construct(MiningLevelSystem $plugin)
    {
        $file = $plugin->getDataFolder() . 'level.db';
        if (file_exists($file)) {
            $this->db = new SQLite3($file, SQLITE3_OPEN_READWRITE);
        } else {
            $this->db = new SQLite3($file, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        }

        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS mining (
            name TEXT NOT NULL PRIMARY KEY,
            level INTEGER NOT NULL,
            exp INTEGER NOT NULL,
            upexp INTEGER NOT NULL
            )'
        );
    }

    /**
     * @param string $name
     * @param integer $level
     * @param integer $exp
     * @param integer $upexp
     * @return void
     */
    public function createPlayerData(string $name, int $level, int $exp, int $upexp)
    {
        $stmt = $this->db->prepare("INSERT INTO mining (name, level, exp, upexp) VALUES (:name, :level, :exp, :upexp)");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':level', $level, SQLITE3_INTEGER);
        $stmt->bindValue(':exp', $exp, SQLITE3_INTEGER);
        $stmt->bindValue(':upexp', $upexp, SQLITE3_INTEGER);
        $stmt->execute();
    }

    /**
     * @param string $name
     * @return integer|null
     */
    public function getLevel(string $name): ?int
    {
        $stmt = $this->db->prepare("SELECT level FROM mining WHERE name = :name");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return $result['level'];
    }

    /**
     * @param string $name
     * @param integer $level
     * @return void
     */
    public function setLevel(string $name, int $level)
    {
        $stmt = $this->db->prepare("UPDATE mining SET level = :level WHERE name = :name");
        $stmt->bindValue(':level', $level, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @param string $name
     * @return integer|null
     */
    public function getExp(string $name): ?int
    {
        $stmt = $this->db->prepare("SELECT exp FROM mining WHERE name = :name");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return $result['exp'];
    }

    /**
     * @param string $name
     * @param integer $exp
     * @return void
     */
    public function setExp(string $name, int $exp)
    {
        $stmt = $this->db->prepare("UPDATE mining SET exp = :exp WHERE name = :name");
        $stmt->bindValue(':exp', $exp, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }

    /**
     * @param string $name
     * @return integer|null
     */
    public function getUpExp(string $name): ?int
    {
        $stmt = $this->db->prepare("SELECT upexp FROM mining WHERE name = :name");
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        return $result['upexp'];
    }

    /**
     * @param string $name
     * @param integer $upexp
     * @return void
     */
    public function setUpExp(string $name, int $upexp)
    {
        $stmt = $this->db->prepare("UPDATE mining SET upexp = :upexp WHERE name = :name");
        $stmt->bindValue(':upexp', $upexp, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->execute();
    }
}

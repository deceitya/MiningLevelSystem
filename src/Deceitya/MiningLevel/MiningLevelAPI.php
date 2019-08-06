<?php

namespace Deceitya\MiningLevel;

use pocketmine\Player;

use Deceitya\MiningLevel\MiningLevelSystem;
use Deceitya\MiningLevel\Database\SQLiteDatabase;

class MiningLevelAPI
{
    /** @var MiningLevelAPI */
    private static $instance;

    public static function getInstance(): MiningLevelAPI
    {
        if (!isset(self::$instance)) {
            self::$instance = new MiningLevelAPI();
        }

        return self::$instance;
    }

    /** @var SQLiteDatabase */
    private $db;

    private function __construct()
    {
    }

    public function init(MiningLevelSystem $plugin)
    {
        $this->db = new SQLiteDatabase($plugin);
    }

    public function deinit()
    {
        $this->db->close();
    }

    /**
     * @param string|Player $player
     * @return void
     */
    public function createPlayerData($player)
    {
        $player = $this->convert2lower($player);

        if (!$this->playerDataExists($player)) {
            $this->db->createPlayerData($player, 1, 0, 2);
        }
    }

    /**
     * @param string|Player $player
     * @return boolean
     */
    public function playerDataExists($player): bool
    {
        return $this->getLevel($player) == null ? false : true;
    }

    /**
     * @param string|Player $player
     * @return integer|null
     */
    public function getLevel($player): ?int
    {
        $player = $this->convert2lower($player);

        return $this->db->getLevel($player);
    }

    /**
     * @param string|Player $player
     * @param integer $level
     * @return void
     */
    public function setLevel($player, int $level)
    {
        $player = $this->convert2lower($player);

        if ($this->playerDataExists($player)) {
            $this->db->setLevel($player, $level);
        }
    }

    /**
     * @param string|Player $player
     * @return integer|null
     */
    public function getExp($player): ?int
    {
        $player = $this->convert2lower($player);

        return $this->db->getExp($player);
    }

    /**
     * @param string|Player $player
     * @param integer $exp
     * @return void
     */
    public function setExp($player, int $exp)
    {
        $player = $this->convert2lower($player);

        if ($this->playerDataExists($player)) {
            $this->db->setExp($player, $exp);
        }
    }

    /**
     * @param string|Player $player
     * @return integer|null
     */
    public function getLevelUpExp($player): ?int
    {
        $player = $this->convert2lower($player);

        return $this->db->getUpExp($player);
    }

    /**
     * @param string|Player $player
     * @param integer $upexp
     * @return void
     */
    public function setLevelUpExp($player, int $upexp)
    {
        $player = $this->convert2lower($player);

        if ($this->playerDataExists($player)) {
            $this->db->setUpExp($player, $upexp);
        }
    }

    /**
     * @param [type] $player
     * @return array
     */
    public function getData($player): array
    {
        $player = $this->convert2lower($player);

        return $this->db->getData($player);
    }

    /**
     * @param string|Player $player
     * @return string
     */
    private function convert2lower($player): string
    {
        if ($player instanceof Player) {
            $player = $player->getName();
        }

        return strtolower($player);
    }
}

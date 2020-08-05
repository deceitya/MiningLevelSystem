<?php

namespace Deceitya\MiningLevel\Event;

use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;

use Deceitya\MiningLevel\MiningLevelAPI;
use Deceitya\MiningLevel\Event\MiningLevelUpEvent;

class EventListener implements Listener
{
    /** @var array */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config->getAll();
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $api = MiningLevelAPI::getInstance();
        $player = $event->getPlayer();

        if (!$api->playerDataExists($player)) {
            $api->createPlayerData($player);
        }
    }

    /**
     * @priority MONITOR
     * @ignoreCancelled
     */
    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->getGamemode() != 0) {
            return;
        }

        $api = MiningLevelAPI::getInstance();
        $block = $event->getBlock();
        $exp = ($this->config[$block->getId() . ':' . $block->getDamage()] ?? $this->config['default'] ?? 0) + $api->getExp($player);

        $up = 0;
        $originalLevel = $api->getLevel($player);
        $level = $originalLevel;
        $upexp = $api->getLevelUpExp($player);
        for ($up = 0; $exp >= $upexp; $up++) {
            $exp -= $upexp;
            $upexp += $level;
            $level++;
        }

        if ($up > 0) {
            $name = $player->getName();
            $player->getServer()->broadcastMessage("[§bMiningLevelSystem§f] {$name}がレベルアップ！ ({$originalLevel} -> {$level})");
            (new MiningLevelUpEvent($player, $originalLevel, $level))->call();
        }

        $api->setLevel($player, $level);
        $api->setExp($player, $exp);
        $api->setLevelUpExp($player, $upexp);
    }
}

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
    /** @var Config */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
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
     * @priority HIGH
     * @ignoreCancelled
     */
    public function onBlockBreak(BlockBreakEvent $event)
    {
        $api = MiningLevelAPI::getInstance();
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $exp = $this->config->get($block->getId() . ':' . $block->getDamage(), $this->config->get('default', 0));

        $up = 0;
        $level = $api->getLevel($player);
        $upexp = $api->getLevelUpExp($player);
        for ($up = 0; $exp >= $upexp; $up++) {
            $exp -= $upexp;
            $upexp += $level;
        }
        $levelup = $level + $up;

        if ($up > 0) {
            $name = $player->getName();
            $player->getServer()->broadcastMessage("[§bMiningLevelSystem§f] {$name}がレベルアップ！　（{$level} -> {$levelup}）");
            (new MiningLevelUpEvent($player, $level, $levelup))->call();
        }

        $api->setLevel($player, $levelup);
        $api->setExp($player, $exp);
        $api->setLevelUpExp($player, $upexp);
    }
}

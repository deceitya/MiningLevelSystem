<?php

namespace Deceitya\MiningLevel;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

use Deceitya\MiningLevel\MiningLevelAPI;
use Deceitya\MiningLevel\Event\EventListener;

class MiningLevelSystem extends PluginBase
{
    public function onEnable()
    {
        $this->saveResource('config.yml');

        MiningLevelAPI::getInstance()->init($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this->getConfig()), $this);
    }

    public function onDisable()
    {
        MiningLevelAPI::getInstance()->deinit();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!isset($args[0])) return false;

        switch ($args[0]) {
            case 'status':
                $name = null;
                if ($sender instanceof Player) {
                    $name = isset($args[1]) ? $args[1] : $sender->getName();
                } else {
                    if (!isset($args[1])) {
                        return false;
                    }

                    $name = $args[1];
                }

                $data = MiningLevelAPI::getInstance()->getData($name);
                if (!empty($data)) {
                    $sender->sendMessage(
                        "[§bMiningSystem§f] {$name}のステータス\n".
                        "レベル: {$data[1]}\n".
                        "経験値: {$data[2]}\n".
                        "レベルアップに必要な経験値: {$data[3]}"
                    );
                } else {
                    $sender->sendMessage("[§bMiningSystem§f] {$name}のデータは存在しません。");
                }

                return true;
            default:
                return false;
        }
    }
}

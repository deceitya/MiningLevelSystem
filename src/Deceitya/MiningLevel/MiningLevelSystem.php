<?php

namespace Deceitya\MiningLevel;

use pocketmine\plugin\PluginBase;

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
}

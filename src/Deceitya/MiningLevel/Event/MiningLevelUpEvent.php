<?php

namespace Deceitya\MiningLevel\Event;

use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class MiningLevelUpEvent extends PlayerEvent
{
    /** @var int */
    private $from;
    /** @var int */
    private $to;

    public function __construct(Player $player, int $from, int $to)
    {
        $this->player = $player;
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
    }
}

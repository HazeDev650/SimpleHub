<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\world\WorldManager;
use Terpz710\SimpleHub\command\HubCommand;
use Terpz710\SimpleHub\command\SetHubCommand;
use Terpz710\SimpleHub\command\DeleteHubCommand;

class Main extends PluginBase implements Listener {

    /** @var WorldManager */
    private $worldManager;

    public function onEnable() : void {
        $this->getServer()->getCommandMap()->register("SimpleHub", new HubCommand());
        $this->getServer()->getCommandMap()->register("SimpleHub", new SetHubCommand());
        $this->getServer()->getCommandMap()->register("SimpleHub", new DeleteHubCommand());
        $this->getWorldManager()->getDefaultWorld();
    }

    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $spawnLocation = $player->getWorld()->getSpawnLocation();
        $player->teleport($spawnLocation);
    }

    public function getWorldManager(): WorldManager {
        if ($this->worldManager === null) {
            $this->worldManager = $this->getServer()->getWorldManager();
        }
        return $this->worldManager;
    }
}

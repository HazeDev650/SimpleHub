<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\world\WorldManager;
use pocketmine\math\Vector3;
use Terpz710\SimpleHub\command\HubCommand;
use Terpz710\SimpleHub\command\SetHubCommand;
use Terpz710\SimpleHub\command\DeleteHubCommand;

class Main extends PluginBase implements Listener {

    /** @var WorldManager */
    private $worldManager;
    
    // Store hub locations for each world
    private $hubLocations = [];

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("SimpleHub", new HubCommand($this));
        $this->getServer()->getCommandMap()->register("SimpleHub", new SetHubCommand($this));
        $this->getServer()->getCommandMap()->register("SimpleHub", new DeleteHubCommand($this));
        $this->getWorldManager()->getDefaultWorld();
    }

    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $spawnLocation = $player->getWorld()->getSpawnLocation();
        $player->teleport($spawnLocation);
    }

    public function setHubLocation(string $worldName, Vector3 $location) {
        $this->hubLocations[$worldName] = $location;
    }

    public function isHubLocationSet(string $worldName): bool {
        return isset($this->hubLocations[$worldName]);
    }

    public function getHubLocation(string $worldName): ?Vector3 {
        return $this->hubLocations[$worldName] ?? null;
    }

    public function getWorldManager(): WorldManager {
        if ($this->worldManager === null) {
            $this->worldManager = $this->getServer()->getWorldManager();
        }
        return $this->worldManager;
    }
}

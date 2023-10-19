<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\WorldManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use Terpz710\SimpleHub\command\HubCommand;
use Terpz710\SimpleHub\command\SetHubCommand;
use Terpz710\SimpleHub\command\DeleteHubCommand;

class Main extends PluginBase implements Listener {

    /** @var WorldManager */
    private $worldManager;

    private $hubLocations = [];

    private $originWorlds = [];

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("SimpleHub", new HubCommand($this));
        $this->getServer()->getCommandMap()->register("SimpleHub", new SetHubCommand($this));
        $this->getServer()->getCommandMap()->register("SimpleHub", new DeleteHubCommand($this));
        $this->getWorldManager()->getDefaultWorld();
        $this->loadHubLocations();
    }

    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        $spawnLocation = $player->getWorld()->getSpawnLocation();
        $player->teleport($spawnLocation);
    }

    public function setHubLocation(string $worldName, Vector3 $location) {
        $this->hubLocations[$worldName] = $location;
        $this->saveHubLocations();
    }

    public function isHubLocationSet(string $worldName): bool {
        return isset($this->hubLocations[$worldName]);
    }

    public function getHubLocation(string $worldName): ?Vector3 {
        return $this->hubLocations[$worldName] ?? null;
    }

    public function setOriginWorld(Player $player, string $worldName) {
        $this->originWorlds[$player->getName()] = $worldName;
    }

    public function getOriginWorld(Player $player): ?string {
        return $this->originWorlds[$player->getName()] ?? null;
    }

    public function getWorldManager(): WorldManager {
        if ($this->worldManager === null) {
            $this->worldManager = $this->getServer()->getWorldManager();
        }
        return $this->worldManager;
    }

    public function saveHubLocations() {
        $config = new Config($this->getDataFolder() . "hublocations.yml", Config::YAML);
        $data = [];
        foreach ($this->hubLocations as $worldName => $location) {
            $data[$worldName] = [
                'x' => $location->getX(),
                'y' => $location->getY(),
                'z' => $location->getZ(),
            ];
        }
        $config->setAll($data);
        $config->save();
    }

    public function loadHubLocations() {
        $config = new Config($this->getDataFolder() . "hublocations.yml", Config::YAML);
        $data = $config->getAll();
        foreach ($data as $worldName => $location) {
            $x = $location['x'];
            $y = $location['y'];
            $z = $location['z'];
            $pos = new Vector3($x, $y, $z);
            $this->hubLocations[$worldName] = $pos;
        }
    }
}

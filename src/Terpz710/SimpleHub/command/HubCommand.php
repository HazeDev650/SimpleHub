<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use Terpz710\SimpleHub\Main;

class HubCommand extends Command implements PluginOwned {
    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(
            "hub",
            "Teleport to hub",
            "/hub",
            ["lobby", "spawn"]
        );
        $this->setPermission("simplehub.hub");
        $this->plugin = $plugin;
    }

    public function getOwningPlugin(): \pocketmine\plugin\Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if (!$this->testPermission($sender)) {
            return;
        }

        if ($sender instanceof Player) {
            $world = $sender->getWorld();
            $worldName = $world->getWorldByName();
            
            if ($this->plugin->isHubLocationSet($worldName)) {
                $hubLocation = $this->plugin->getHubLocation($worldName);
                $sender->teleport($hubLocation);
                $sender->sendMessage(TextFormat::GREEN . "You have been teleported to the hub in world $worldName");
            } else {
                $sender->sendMessage(TextFormat::RED . "Hub location is not set for this world.");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by a player");
        }
    }
}

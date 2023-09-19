<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use Terpz710\SimpleHub\Main;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

class DeleteHubCommand extends Command implements PluginOwned {
    use PluginOwnedTrait;

    public function __construct(Main $plugin) {
        parent::__construct(
            "deletehub",
            "Delete the hub location",
            "/deletehub",
            ["removehub"]
        );
        $this->setPermission("deletehub.command");
        $this->setOwningPlugin($plugin);
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if (!$this->testPermission($sender)) {
            return;
        }

        if ($sender instanceof Player) {
            $spawnLocation = $sender->getWorld()->getSpawnLocation();
            $sender->getWorld()->setSpawnLocation($spawnLocation);

            $sender->sendMessage(TextFormat::GREEN . "Hub location deleted. Players will now spawn at the world spawn.");
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players");
        }
    }
}

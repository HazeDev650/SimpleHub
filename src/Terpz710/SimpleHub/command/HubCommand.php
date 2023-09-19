<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use Terpz710\SimpleHub\Main;

class HubCommand extends Command {

    public function __construct() {
        parent::__construct(
            "hub",
            "Teleport to hub",
            "/hub",
            ["lobby", "spawn"]
        );
        $this->setPermission("simplehub.hub");
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if (!$this->testPermission($sender)) {
            return;
        }

        if ($sender instanceof Player) {
            $spawnLocation = $sender->getWorld()->getSpawnLocation();
            $sender->teleport($spawnLocation);
            $sender->sendMessage(TextFormat::GREEN . "You have been teleported to hub");
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by a player");
        }
    }
}

<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use Terpz710\SimpleHub\Main;

class DeleteHubCommand extends Command {

    public function __construct() {
        parent::__construct(
            "deletehub",
            "Delete the hub location",
            "/deletehub",
            ["removehub"]
        );
        $this->setPermission("deletehub.command");
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

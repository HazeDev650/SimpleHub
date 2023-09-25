<?php

declare(strict_types=1);

namespace Terpz710\SimpleHub\command;

use pocketmine\command\Command;
use pocketmine\command.CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginOwned;
use Terpz710\SimpleHub\Main;

class SetHubCommand extends Command implements PluginOwned {
    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(
            "sethub",
            "Set the hub",
            "/sethub <x> <y> <z>",
            ["setlobby", "setspawn"]
        );
        $this->setPermission("simplehub.sethub");
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
            if (isset($args[0]) && isset($args[1]) && isset($args[2])) {
                $x = (float)$args[0];
                $y = (float)$args[1];
                $z = (float)$args[2];

                $pos = new Vector3($x, $y, $z);
                $pos->round();

                $world = $sender->getWorld();
                $worldName = $world->getName();

                $this->plugin->setHubLocation($worldName, $pos);

                $sender->sendMessage(TextFormat::GREEN . "Hub location set to ($x, $y, $z) in world $worldName");
            } else {
                $sender->sendMessage(TextFormat::RED . "Please enter all three coordinates");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players");
        }
    }
}

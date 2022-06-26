<?php

namespace r3pt1s\ChatFilter\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\PluginOwned;
use r3pt1s\ChatFilter\ChatFilter;
use r3pt1s\ChatFilter\configuration\Configuration;
use r3pt1s\ChatFilter\message\MessageKeys;

class ChatFilterReloadCommand extends Command implements PluginOwned {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("chatfilter.reload");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            Configuration::getInstance()->reload();
            $sender->sendMessage(MessageKeys::RELOADED());
        } else {
            $sender->sendMessage(MessageKeys::NO_PERMS());
        }
        return true;
    }

    public function getOwningPlugin(): ChatFilter {
        return ChatFilter::getInstance();
    }
}
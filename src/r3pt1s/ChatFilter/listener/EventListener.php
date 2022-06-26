<?php

namespace r3pt1s\ChatFilter\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\ChatFilter\ChatFilter;
use r3pt1s\ChatFilter\manager\PlayerManager;
use r3pt1s\ChatFilter\message\MessageKeys;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        PlayerManager::registerPlayer($event->getPlayer());
    }

    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if (ChatFilter::getInstance()->filter($player, $message, $type)) {
            $event->cancel();
            $player->sendMessage(MessageKeys::typeAssignedMessage($type));
        } else {
            PlayerManager::setLastMessage($player, $message);
        }
    }

    public function onCommand(CommandEvent $event) {
        $sender = $event->getSender();
        if (!$sender instanceof Player) return;
        $args = explode(" ", trim($event->getCommand()));
        $command = Server::getInstance()->getCommandMap()->getCommand(array_shift($args));
        if ($command === null) return;
        $do = false;

        if ($command->getName() == "tell") {
            if (count($args) >= 2) {
                array_shift($args);
                $do = true;
            }
        } else if ($command->getName() == "me") {
            if (count($args) >= 1) {
                $do = true;
            }
        }

        if ($do) {
            if (ChatFilter::getInstance()->filter($sender, implode(" ", $args), $type)) {
                $event->cancel();
                $sender->sendMessage(MessageKeys::typeAssignedMessage($type));
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        PlayerManager::unregisterPlayer($event->getPlayer());
    }
}
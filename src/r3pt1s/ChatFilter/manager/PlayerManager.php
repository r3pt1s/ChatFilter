<?php

namespace r3pt1s\ChatFilter\manager;

use JetBrains\PhpStorm\Pure;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\ChatFilter\configuration\Configuration;

class PlayerManager {

    private static array $players = [];

    public static function registerPlayer(Player|string $player) {
        $player = $player instanceof Player ? $player->getName() : $player;
        self::$players[$player] = [
            "last_message" => null,
            "next_message" => 0
        ];
    }

    public static function setLastMessage(Player|string $player, string $message) {
        $player = $player instanceof Player ? $player->getName() : $player;
        self::$players[$player] = [
            "last_message" => $message,
            "next_message" => Server::getInstance()->getTick() + Configuration::getInstance()->getWaitTime()
        ];
    }

    #[Pure] public static function getLastMessage(Player|string $player): ?string {
        $player = $player instanceof Player ? $player->getName() : $player;
        return (isset(self::$players[$player]) ? self::$players[$player]["last_message"] ?? null : null);
    }

    #[Pure] public static function getNextMessage(Player|string $player): int {
        $player = $player instanceof Player ? $player->getName() : $player;
        return (isset(self::$players[$player]) ? self::$players[$player]["next_message"] ?? 0 : 0);
    }

    #[Pure] public static function hasLastMessage(Player|string $player): bool {
        return self::getLastMessage($player) !== null;
    }

    public static function hasToWait(Player|string $player): bool {
        return self::getNextMessage($player) > Server::getInstance()->getTick();
    }

    public static function unregisterPlayer(Player|string $player) {
        $player = $player instanceof Player ? $player->getName() : $player;
        if (isset(self::$players[$player])) unset(self::$players[$player]);
    }
}
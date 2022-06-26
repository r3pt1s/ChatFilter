<?php

namespace r3pt1s\ChatFilter;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use r3pt1s\ChatFilter\command\ChatFilterReloadCommand;
use r3pt1s\ChatFilter\configuration\Configuration;
use r3pt1s\ChatFilter\listener\EventListener;
use r3pt1s\ChatFilter\manager\PlayerManager;

class ChatFilter extends PluginBase {

    public const TYPE_BAD_WORD = 0;
    public const TYPE_DOMAIN = 1;
    public const TYPE_SPAM = 2;
    public const TYPE_CAPS_LOCK = 3;
    public const TYPE_SAME_MESSAGE = 4;

    private static self $instance;
    private Configuration $configuration;

    protected function onEnable(): void {
        self::$instance = $this;
        $this->saveResource("messages.yml");
        $this->saveDefaultConfig();
        $this->configuration = new Configuration($this->getConfig());

        $this->registerPermissions();
        $this->getServer()->getCommandMap()->register("ChatFilter", new ChatFilterReloadCommand("chatfilterreload", "ChatFilterReload Command", "", ["cfreload", "cfr"]));
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function registerPermissions() {
        DefaultPermissions::registerPermission(new Permission("chatfilter.bypass"), [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);
        DefaultPermissions::registerPermission(new Permission("chatfilter.reload"), [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);
    }

    public function filter(Player $player, string $message, ?int &$type = null): bool {
        if ($player->hasPermission("chatfilter.bypass")) return false;
        if (PlayerManager::hasToWait($player)) {
            $type = self::TYPE_SPAM;
            return true;
        }

        if (PlayerManager::hasLastMessage($player)) {
            if (TextFormat::clean(PlayerManager::getLastMessage($player), true) == TextFormat::clean($message, true)) {
                $type = self::TYPE_SAME_MESSAGE;
                return true;
            }
        }

        foreach (explode(" ", $message) as $part) {
            foreach (Configuration::getInstance()->getBlockedDomains() as $domain) {
                if (Configuration::getInstance()->isAllowedDomain($domain) || Configuration::getInstance()->isAllowedDomain($part)) continue;
                if (str_contains($part, $domain)) {
                    $type = self::TYPE_DOMAIN;
                    return true;
                }
            }
        }

        foreach (Configuration::getInstance()->getBadWords() as $word) {
            if (str_contains($message, $word)) {
                $type = self::TYPE_BAD_WORD;
                return true;
            }
        }

        $messageLength = strlen(str_replace(" ", "", $message));
        preg_match_all("/[A-Z]/", $message, $matches);
        if (Configuration::getInstance()->isCapsLimitInPercentage()) {
            $capsPercentage = (count($matches[0]) / $messageLength * 100);
            if ($capsPercentage >= Configuration::getInstance()->getCapsLimit()) {
                $type = self::TYPE_CAPS_LOCK;
                return true;
            }
        } else {
            if (count($matches[0]) >= Configuration::getInstance()->getCapsLimit()) {
                $type = self::TYPE_CAPS_LOCK;
                return true;
            }
        }
        return false;
    }

    public function getMessagesConfig(): Config {
        return new Config($this->getDataFolder() . "messages.yml", 2);
    }

    public function getConfiguration(): Configuration {
        return $this->configuration;
    }

    public static function getInstance(): ChatFilter {
        return self::$instance;
    }
}
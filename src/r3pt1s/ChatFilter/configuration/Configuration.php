<?php

namespace r3pt1s\ChatFilter\configuration;

use pocketmine\utils\Config;

class Configuration {

    private static self $instance;
    private Config $config;
    private bool $block_bad_words = true;
    private bool $block_domains = true;
    private bool $block_spam = true;
    private bool $block_caps_lock = true;
    private bool $allow_same_messages = false;
    private bool $allow_spam_commands = false;
    private int $waitTime = 20;
    private int $caps_limit = 50;
    private bool $isCapsLimitInPercentage = true;
    private array $blocked_domains = [];
    private array $allowed_domains = [];
    private array $bad_words = ["loser"];

    public function __construct(Config $config) {
        self::$instance = $this;
        $this->config = $config;
        $this->load();
    }

    public function load() {
        if ($this->config->exists("block")) {
            $this->block_bad_words = boolval($this->config->getNested("block.bad-words", true));
            $this->block_domains = boolval($this->config->getNested("block.domains", true));
            $this->block_spam = boolval($this->config->getNested("block.spam", true));
            $this->block_caps_lock = boolval($this->config->getNested("block.caps", true));
        }

        $this->allow_same_messages = boolval($this->config->get("allow-same-messages", false));
        $this->allow_spam_commands = boolval($this->config->get("allow-spam-commands", false));
        $this->waitTime = ($this->config->exists("wait-time") ? (is_numeric($this->config->get("wait-time")) ? intval($this->config->get("wait-time")) : 20) : 20);
        if ($this->config->exists("caps-limit")) {
            if (str_contains($this->config->get("caps-limit"), "%")) {
                $percentage = explode("%", $this->config->get("caps-limit"))[0];
                $this->caps_limit = $percentage;
            } else {
                $this->caps_limit = (is_numeric($this->config->get("caps-limit")) ? intval($this->config->get("caps-limit")) : 50);
                $this->isCapsLimitInPercentage = false;
            }
        }

        if ($this->config->exists("blocked-domains")) $this->blocked_domains = (is_array($this->config->get("blocked-domains", [])) ? $this->config->get("blocked-domains", []) : []);
        if ($this->config->exists("allowed-domains")) $this->allowed_domains = (is_array($this->config->get("allowed-domains", [])) ? $this->config->get("allowed-domains", []) : []);
        if ($this->config->exists("bad-words")) $this->bad_words = (is_array($this->config->get("bad-words", ["loser"])) ? $this->config->get("bad-words", ["loser"]) : ["loser"]);
    }

    public function reload() {
        $this->config->reload();
        $this->load();
    }

    public function isBlockBadWords(): bool {
        return $this->block_bad_words;
    }

    public function isBlockDomains(): bool {
        return $this->block_domains;
    }

    public function isBlockSpam(): bool {
        return $this->block_spam;
    }

    public function isBlockCapsLock(): bool {
        return $this->block_caps_lock;
    }

    public function isAllowSameMessages(): bool {
        return $this->allow_same_messages;
    }

    public function isAllowSpamCommands(): bool {
        return $this->allow_spam_commands;
    }

    public function getWaitTime(): int {
        return $this->waitTime;
    }

    public function getCapsLimit(): int {
        return $this->caps_limit;
    }

    public function isCapsLimitInPercentage(): bool {
        return $this->isCapsLimitInPercentage;
    }

    public function getBlockedDomains(): array {
        return $this->blocked_domains;
    }

    public function isBlockedDomain(string $domain): bool {
        return in_array($domain, $this->blocked_domains);
    }

    public function getAllowedDomains(): array {
        return $this->allowed_domains;
    }

    public function isAllowedDomain(string $domain): bool {
        return in_array($domain, $this->allowed_domains);
    }

    public function getBadWords(): array {
        return $this->bad_words;
    }

    public function getConfig(): Config {
        return $this->config;
    }

    public static function getInstance(): Configuration {
        return self::$instance;
    }
}
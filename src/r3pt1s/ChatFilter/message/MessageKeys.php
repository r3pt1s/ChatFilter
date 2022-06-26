<?php

namespace r3pt1s\ChatFilter\message;

use pocketmine\utils\RegistryTrait;
use r3pt1s\ChatFilter\ChatFilter;

/**
 * @method static Message NO_PERMS()
 * @method static Message RELOADED()
 * @method static Message BAD_WORD()
 * @method static Message DOMAIN()
 * @method static Message SPAM()
 * @method static Message CAPS_LOCK()
 * @method static Message SAME_MESSAGE()
 */

final class MessageKeys {
    use RegistryTrait;

    protected static function setup(): void {
        self::_registryRegister("no_perms", new Message("message_no_perms"));
        self::_registryRegister("reloaded", new Message("message_reloaded"));
        self::_registryRegister("bad_word", new Message("message_bad_word"));
        self::_registryRegister("domain", new Message("message_domain"));
        self::_registryRegister("spam", new Message("message_spam"));
        self::_registryRegister("caps_lock", new Message("message_caps_lock"));
        self::_registryRegister("same_message", new Message("message_same_message"));
    }

    public static function typeAssignedMessage(int $type): ?Message {
        if ($type == ChatFilter::TYPE_BAD_WORD) {
            return self::BAD_WORD();
        } else if ($type == ChatFilter::TYPE_DOMAIN) {
            return self::DOMAIN();
        } else if ($type == ChatFilter::TYPE_SPAM) {
            return self::SPAM();
        } else if ($type == ChatFilter::TYPE_CAPS_LOCK) {
            return self::CAPS_LOCK();
        } else if ($type == ChatFilter::TYPE_SAME_MESSAGE) {
            return self::SAME_MESSAGE();
        }
        return null;
    }
}
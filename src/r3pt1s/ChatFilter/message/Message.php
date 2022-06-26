<?php

namespace r3pt1s\ChatFilter\message;

use r3pt1s\ChatFilter\ChatFilter;

class Message {

    private string $key;
    private array $parameters;

    public function __construct(string $key = "", array $parameters = []) {
        $this->key = $key;
        $this->parameters = $parameters;
    }

    public function key(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function parameters(array $parameters): self {
        $this->parameters = $this->parameters + $parameters;
        return $this;
    }

    public function parse(): string {
        $prefix = ChatFilter::getInstance()->getMessagesConfig()->get("prefix", "§8» §c§lChatFilter §r§8| §7");
        $message = str_replace(["{PREFIX}", "{prefix}"], $prefix, ChatFilter::getInstance()->getMessagesConfig()->get($this->key, $this->key));
        foreach ($this->parameters as $name => $parameter) $message = str_replace("{" . $name . "}", $parameter, $message);
        return $message;
    }

    public function getKey(): string {
        return $this->key;
    }

    public function getParameters(): array {
        return $this->parameters;
    }

    public function __toString(): string {
        return $this->parse();
    }
}
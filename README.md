# [ChatFilter](https://poggit.pmmp.io/p/Chat-Filter/1.0) [![](https://poggit.pmmp.io/shield.state/Chat-Filter)](https://poggit.pmmp.io/p/Chat-Filter) [![](https://poggit.pmmp.io/shield.dl.total/Chat-Filter)](https://poggit.pmmp.io/p/Chat-Filter)

## Features
- **Spam** Block
- **Domains** Block
- **Bad-Words** Block
- **Caps-Lock** Block
- **Same-Messages** Block
- **Wait-Time** between messages

## Commands
| Usage             | Description       | Aliases       | Permission        |
|-------------------|-------------------|---------------|-------------------|
| /chatfilterreload | Reload the config | cfr, cfreload | chatfilter.reload |

## Permissions
| Name              | Description       |
|-------------------|-------------------|
| chatfilter.bypass | Bypass the blocks |

## Configuration
```yaml
block:
  bad-words: true
  domains: true
  spam: true
  caps-lock: true

allow-same-messages: false
allow-spam-commands: false # spamming commands
wait-time: 20 # wait time between messages in ticks (1 tick = 0,05 seconds | 20 ticks = 1 second)
# percentage is allowed
# Example with percentage: caps-limit: 50% (if 50% of the message is in caps the player will be warned)
# Example without percentage: caps-limit: 50 (if 50 symbols of the message are in caps the player will be warned)
caps-limit: 50%

#blocked-domains:
# - ".de"
# - "discord.gg"
blocked-domains: []
#allowed-domains:
# - "discord.gg/invite/code" for your discord server or something else
allowed-domains: []
bad-words:
  - "loser"
```

## Messages
```yaml
prefix: "§8» §c§lChatFilter §r§8| §7"
message_no_perms: "{PREFIX}You don't have the permission to use this command!"
message_reloaded: "{PREFIX}Successful reloaded."
message_bad_word: "{PREFIX}Please don't use a bad word!"
message_domain: "{PREFIX}Please don't send domains in the chat!"
message_spam: "{PREFIX}Please wait before sending a message!"
message_caps_lock: "{PREFIX}Please deactivate your caps lock!"
message_same_message: "{PREFIX}Please don't send the same messages in a row!"
```

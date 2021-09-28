# ifttt-notification-ws
Notification PHP web service for IFTTT:

 - Rate limiting based on threshold: e.g. ignore multiple of the same notification received within 5 minutes
 - Logs all events to MySQL database


## Configuration:

Change the database configuration in config.php:

```php
$config = [
  'ifttt_key'=>'IFTTT_KEY',
  'ws_secret'=>'WS_SECRET',
  'mysql' => [
    'server'=>'SERVER_URL',
    'database'=>'DATABASE_NAME',
    'username'=>'MYSQL_USERNAME',
    'password'=>'MYSQL_PASSWORD'
  ]
];
```

- `IFTTT_KEY` = Get by clicking "Documentation" at: https://ifttt.com/maker_webhooks/
- `POST_SECRET` = Expected by the HTTP request variable: $_POST['s'] to prevent rogue injection (set this to something not easily guessed).

*This follows the IFTTT webhook examples found in their [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ)*

### Trigger:
In IFTTT, under the `IF` section, create a new `webhook` trigger

You can test your trigger by invoking the URL manually: `https://maker.ifttt.com/trigger/tg_pop_notification/with/key/<your_key>?value1=AAA&value2=BBB&value3=CCC`


### Action:
In IFTTT, under the `THEN` section, create a new `webhook` action

**Use the following options:**
1. **URL:** `https://your.domain/path/to/your/deployment`
2. **Method:** `POST`
3. **Body:** `s=**your_secret**&device=<<<{{DeviceName}}>>>&value=<<<{{Target}}>>>&time=<<<{{CreatedAt}}>>>`
   - set `your_secret` to your secret in `config.php`
   - The `<<<...>>>` is what IFTTT uses for escaping content (e.g. preserving line breaks, etc)

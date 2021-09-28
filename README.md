# ifttt-notification-ws
PHP web service for IFTTT 2-way interaction:

 - Web service for 2-way interaction with IFTTT
 - Logs events to MySQL database
 - Rate limiting based on custom threshold: e.g. ignore multiple of the same notification received within 5 minutes


## How it works:
This works in 2 parts.
1. You need to make an IFTTT applet for receiving notifications from the web service.
   - This applet gets triggered whenever the webservice calls it ("IF" webhook trigger)
   - Provides values for the IFTTT "THEN" action to use in whatever way you want e.g. Telegram message, phone popup notification, email, etc...

2. You need to invoke the webservice:
   - Call this web service ("THEN" webhook action)
   - Provide optional configuration


## Configuration

Change the database configuration in config.php:

```php
$config = [
  'ifttt_key'=>'IFTTT_KEY',
  'ws_secret'=>'POST_SECRET',
  'default_rate_limit_s'=> 900, // default rate limit in seconds (if not specified by request, use this rate limit)
  'mysql' => [
    'server'=>'SERVER_URL',
    'database'=>'DATABASE_NAME',
    'username'=>'MYSQL_USERNAME',
    'password'=>'MYSQL_PASSWORD'
  ]
];
```
## Applet Integration

- `IFTTT_KEY` = Found by clicking "Documentation" at: https://ifttt.com/maker_webhooks/
- `POST_SECRET` = Expected by the HTTP request variable: $_POST['s'] to prevent rogue injection (set this to something not easily guessed).

*This follows the IFTTT webhook examples found in their [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ)*

### Notification Applet:
1. Create a new IFTTT applet
2. In the `IF` section, create a new `webhook` trigger:
`https://maker.ifttt.com/trigger/TRIGGER_NAME/with/key/IFTTT_KEY?value1={AAA}&value2={BBB}&value3={CCC}`
   - `TRIGGER_NAME` = identifier for this specific applet, e.g. `home_notifications`.
   - `IFTTT_KEY` = Found by clicking "Documentation" at: https://ifttt.com/maker_webhooks/



### Applet integration:
In IFTTT, under the `THEN` section, create a new `webhook` action

**Use the following options:**
1. **URL:** `https://your.domain/path/to/your/deployment`
2. **Method:** `POST`
3. **Body:** `secret=YOUR_SECRET&trigger=TRIGGER_NAME&rate=900&device=<<<{{DeviceName}}>>>&value=<<<{{Target}}>>>&time=<<<{{CreatedAt}}>>>`
   - `secret` = authentication secret you made in `config.php`
   - `trigger` = identifier for using in the notification applet
   - `rate` = (optional) the rate limit - set this to ignore duplicate requests within short period of time. If not set, defaults to `default_rate_limit_s`'s value' found in `config.php`
   - `device` = device name to send in notification & insert into notification table in database
   - `value` = device value to send in notification & insert into notification table in database
   - `time` = time of trigger (optional) - if not provided, execution time of web service will be recorded.
   - `<<<...>>>` is what IFTTT uses for escaping content (e.g. preserving line breaks, etc)
   - `{...}` placeholders provided by IFTTT trigger

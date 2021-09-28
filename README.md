# ifttt-notification-ws
Notification PHP web service for IFTTT:

 - Rate limiting based on threshold: e.g. ignore multiple of the same notification received within 5 minutes
 - Logs all events to MySQL database


## Configuration:

Change the database configuration in config.php:
```php
$config = [
  'secret'=>'<your_secret>',
  'mysql' => [
    'server'=>'<your.server.url>',
    'database'=>'<your_database_name>',
    'username'=>'<your_username>',
    'password'=>'<your_password>'
  ]
];
```

*This follows the IFTTT webhook examples found in their [FAQ](https://help.ifttt.com/hc/en-us/articles/115010230347-Webhooks-service-FAQ)*

### Trigger:
*In IFTTT, under the `IF` section, create a new `webhook` trigger*


### Action:
*In IFTTT, under the `THEN` section, create a new `webhook` action*

**Use the following options:**
1. **URL:** `https://your.domain/path/to/your/deployment`
2. **Method:** `POST`
3. **Body:** `s=**your_secret**&device=<<<{{DeviceName}}>>>&value=<<<{{Target}}>>>&time=<<<{{CreatedAt}}>>>`
   - set `your_secret` to your secret in `config.php`
   - *The `<<<...>>>` is what IFTTT uses for escaping content (e.g. preserving line breaks, etc)*

Installation
============

This document will guide you through the process of installing Yii2-autoparser using **composer**. Installation is a quick
and easy three-step process.

Step 1: Download Yii2-user using composer
-----------------------------------------

Add `"deka6pb/yii2-autoparser": "*"` to the require section of your **composer.json** file and run
`composer update` to download and install Yii2-autoparser.

Step 2: Configure your application
------------------------------------

> **NOTE:** Make sure that you don't have any `user` component configuration in your config files.

Add following lines to your main configuration file:

```php
'modules' => [
    'autoparser' => [
        'class' => 'deka6pb\yii2-autoparser\Module',
        'consumers' => $params['consumers'],
        'providers' => $params['providers'],
    ],
],
```

Step 3: Update database schema
------------------------------

> **NOTE:** Make sure that you have properly configured **db** application component.

After you downloaded and configured Yii2-user, the last thing you need to do is updating your database schema by
applying
the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/deka6pb/yii2-autoparser/migrations
```

Step 4: Add Consumers and Providers
------------------------------

Add following lines to your ```params.php``` configuration file:

```
'providers' => [
    'provider1' => [
        'class' => 'path to provider',
        'count' => 1,
        'homepage' => 'website',
        'on' => false
    ],
    ...
],
'consumers' => [
    'VkConsumer' => [
        'class' => 'backend\components\Consumers\VkConsumer',
        'APP_ID' => 'app_id',
        'GROUP_ID' => 'group_id',
        'on' => true
    ],
    ...
],
```

For example:

```
class VkConsumer extends PostDataConsumerBase {
    ...
}
```

```
class PikabuProvider extends PostDataProviderBase {
    ...
}
```
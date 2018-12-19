Yii2 wrap of [autoxloo/apns](https://github.com/VasylDmytruk/apns)
=========================
Yii2 wrap of autoxloo/apns

>Note: This package is not supported properly

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist autoxloo/yii2-apns "*"
```

or

```
composer require --prefer-dist autoxloo/yii2-apns "*"
```

or add

```
"autoxloo/yii2-apns": "*"
```

to the require section of your `composer.json` file.

Config
------

To send push notification you should have apple .pem certificate.

In your application config add:

```
// ...
'components' => [
        // ...
        'apnsNotification' => [
            'class' => \autoxloo\yii2\apns\APNSNotification::class,
            'appleCertPath' => __DIR__ . '/wxv_cert.pem',
            'apiUrl' => 'https://api.push.apple.com/3/device',                  // default
            'apiUrlDev' => 'https://api.development.push.apple.com/3/device',   // default
            'apnsPort' => 443,                                                  // default
            'pushTimeOut' => 10,                                                // default
        ],
],
```

`AppleNotificationServer` sends push notification first on `$apiUrl` (https://api.push.apple.com/3/device)
if not success (not status code `200`), then sends on `$apiUrlDev` (https://api.development.push.apple.com/3/device).
If you don't want to send push notification on $apiUrlDev set it value to `false`:

```php
// ...
'components' => [
        // ...
        'apnsNotification' => [
            'class' => \autoxloo\yii2\apns\APNSNotification::class,
            'appleCertPath' => __DIR__ . '/wxv_cert.pem',
            'apiUrl' => 'https://api.push.apple.com/3/device',                  // default
            'apiUrlDev' => false,
            'apnsPort' => 443,                                                  // default
            'pushTimeOut' => 10,                                                // default
        ],
],
```

Also if you want to send push notification only on dev url, you can do so by setting `apiUrl` with dev url:

```php
// ...
'components' => [
        // ...
        'apnsNotification' => [
            'class' => \autoxloo\yii2\apns\APNSNotification::class,
            'appleCertPath' => __DIR__ . '/wxv_cert.pem',
            'apiUrl' => 'https://api.development.push.apple.com/3/device',
            'apiUrlDev' => false,
            'apnsPort' => 443,                                                  // default
            'pushTimeOut' => 10,                                                // default
        ],
],
```

You have to install curl with http2 support:
--------------------------------------------

```
cd ~
sudo apt-get install build-essential nghttp2 libnghttp2-dev libssl-dev
wget https://curl.haxx.se/download/curl-7.58.0.tar.gz
tar -xvf curl-7.58.0.tar.gz
cd curl-7.58.0
./configure --with-nghttp2 --prefix=/usr/local --with-ssl=/usr/local/ssl
make
sudo make install
sudo ldconfig
sudo reboot
```

Info from [https://askubuntu.com/questions/884899/how-do-i-install-curl-with-http2-support](https://askubuntu.com/questions/884899/how-do-i-install-curl-with-http2-support)

If not helped, try [https://serversforhackers.com/c/curl-with-http2-support](https://serversforhackers.com/c/curl-with-http2-support)

Usage
-----

Sending push notification:

```php
$token = 'some device token';
$payload = [
    'some key1' => 'some value1',
    'some key2' => 'some value2',
];

$response = \Yii::$app->apnsNotification->send($token, $payload);
```

or if you want to send to many tokens:

```php
$tokens = [
    'some device token',
    'some other device token',
];
$payload = [
    'some key1' => 'some value1',
    'some key2' => 'some value2',
];

$response = \Yii::$app->apnsNotification->sendToMany($tokens, $payload);
```

See [autoxloo/apns](https://github.com/VasylDmytruk/apns) for more details.

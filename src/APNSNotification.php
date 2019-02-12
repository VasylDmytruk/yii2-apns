<?php

namespace autoxloo\yii2\apns;

use autoxloo\apns\AppleNotificationServer;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class APNSNotification Yii2 wrap of [[autoxloo\apns\AppleNotificationServer]].
 * @see AppleNotificationServer
 *
 * @property string $topic
 */
class APNSNotification extends Component
{
    /**
     * @var string Apple API notification url.
     */
    public $apiUrl = 'https://api.push.apple.com/3/device';
    /**
     * @var string Apple API notification development url.
     */
    public $apiUrlDev = 'https://api.development.push.apple.com/3/device';
    /**
     * @var string Path to apple .pem certificate.
     */
    public $appleCertPath;
    /**
     * @var int APNS posrt.
     */
    public $apnsPort = 443;
    /**
     * @var int Push timeout.
     */
    public $pushTimeOut = 10;
    /**
     * @var string|null 'apns-topic' header
     * @see https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CommunicatingwithAPNs.html
     */
    private $topic = null;

    /**
     * @var AppleNotificationServer
     */
    protected $apns;


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (empty($this->appleCertPath)) {
            throw new InvalidConfigException('Param "appleCertPath" is required');
        }

        if (!is_string($this->appleCertPath) || !file_exists($this->appleCertPath)) {
            throw new InvalidConfigException('Param "$appleCertPath" must be a valid string path to file');
        }

        $this->appleCertPath = realpath(Yii::getAlias($this->appleCertPath));

        $this->apns = new AppleNotificationServer(
            $this->appleCertPath,
            $this->apiUrl,
            $this->apiUrlDev,
            $this->apnsPort,
            $this->pushTimeOut,
            $this->topic
        );
    }

    /**
     * Sends notification to many recipients (`$tokens`).
     *
     * @param array $tokens List of tokens of devices to send push notification on.
     * @param array $payload APNS payload data (will be json encoded).
     *
     * @return array List of status codes with response messages.
     */
    public function sendToMany(array $tokens, array $payload)
    {
        return $this->apns->sendToMany($tokens, $payload);
    }

    /**
     * Sends notification to recipient (`$token`).
     *
     * @param string $token Token of device to send push notification on.
     * @param array $payload APNS payload data (will be json encoded).
     *
     * @return array Status code with response message.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function send($token, array $payload)
    {
        return $this->apns->send($token, $payload);
    }

    /**
     * @return null|string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param null|string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        if ($this->apns) {
            $this->apns->setTopic($topic);
        }
    }
}

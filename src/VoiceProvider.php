<?php

namespace Huying\Sms\RongLian;

use GuzzleHttp\Exception\GuzzleException;
use Huying\Sms\AbstractProvider;
use Huying\Sms\Message;
use Huying\Sms\ProviderException;
use Psr\Http\Message\ResponseInterface;

/**
 * 容联短信平台接口实现
 *
 * Class Provider
 */
class VoiceProvider extends Provider
{
    /**
     * 返回短信接口必须的参数
     * @param $key
     * @return array
     */
    protected function getRequiredOptions($key)
    {
        if ($key == self::PROVIDER_OPTIONS) {
            return [
                'accountSid',
                'authToken',
                'appId',
            ];
        } elseif ($key == self::MESSAGE_OPTIONS) {
            return [
                'recipients',
                'data',
            ];
        } else {
            return []; // @codeCoverageIgnore
        }
    }

    /**
     * 返回请求链接
     *
     * @param Message $message
     * @return string
     * @throws \RuntimeException
     */
    protected function getUrl(Message $message)
    {
        return $this->restUrl.'/'.$this->softVersion
            .'/Accounts/'.$this->accountSid
            .'/Calls/VoiceVerify?sig='.strtoupper(md5($this->accountSid.$this->authToken.$this->getTimestamp()));
    }

    /**
     * 返回请求短信接口时的 payload
     *
     * @param Message $message
     * @return string
     * @throws \RuntimeException
     */
    protected function getRequestPayload(Message $message)
    {
        $recipients = implode(',', $message->getRecipients());
        $data = $message->getData();

        return json_encode([
            'to' => $recipients,
            'appId' => $this->appId,
        ] + $data);
    }

    /**
     * 获取短信供应商名称
     *
     * @return string
     */
    public function getName()
    {
        return 'RongLian';
    }
}

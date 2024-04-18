<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class SocialService
 * @package App\Service
 */
class SocialService
{
    private $client;

    const NAME_GOOGLE = 'google';
    const NAME_FACEBOOK = 'facebook';
    const NAME_DISCORD = 'discord';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public static function urlInfo()
    {
        return [
            self::NAME_GOOGLE => 'https://www.googleapis.com/oauth2/v1/userinfo',
            self::NAME_FACEBOOK => 'https://graph.facebook.com/v8.0/me?fields=name,email,picture',
            self::NAME_DISCORD => 'https://discord.com/api/users/@me',
        ];
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function send($method, $url, $options)
    {
        $response = $this->client->request($method, $url, $options);

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        return $response->toArray();
    }

    /**
     * @param $name
     * @param $data
     * @return string[]
     */
    protected static function normalizeUserInfo($name, $data)
    {
        $info = [
            'email' => '',
            'name' => '',
            'avatar' => '',
        ];

        switch ($name) {
            case self::NAME_GOOGLE:
                $info['email']  = $data['email'] ?? '';
                $info['name']   = $data['name'] ?? '';
                $info['avatar'] = $data['picture'] ?? '';
                break;
            case self::NAME_FACEBOOK:
                $info['email']  = $data['email'] ?? '';
                $info['name']   = $data['name'] ?? '';
                $info['avatar'] = $data['picture']['data']['url'] ?? '';
                break;
            case self::NAME_DISCORD:
                $info['email']  = $data['email'] ?? '';
                $info['name']   = $data['username'] ?? '';
                $info['avatar'] = $data['avatar'] ?? '';
                break;

        }

        return $info;
    }

    /**
     * @param string $name
     * @param string $token
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getUserInfo(string $name, string $token)
    {
        $url = self::urlInfo()[$name] ?? null;

        if (empty($url)) {
            return null;
        }

        $method = 'GET';
        $options = [
            'auth_bearer' => $token,
        ];

        $data = $this->send($method, $url, $options);

        return self::normalizeUserInfo($name, $data);
    }
}

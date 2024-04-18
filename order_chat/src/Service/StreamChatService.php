<?php

declare(strict_types=1);

namespace App\Service;

use GetStream\StreamChat\Client;

class StreamChatService
{
    /** @var Client */
    protected $streamChat;

    /**
     * @param string|null $key
     * @param string|null $secret
     */
    public function __construct(?string $key, ?string $secret)
    {
        $this->streamChat = new Client($key, $secret);
    }

    /**
     * @param string $userId
     * @return string
     * @throws \GetStream\StreamChat\StreamException
     */
    public function generateToken(string $userId): string
    {
        return $this->streamChat->createToken($userId);
    }
}

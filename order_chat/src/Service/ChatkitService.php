<?php

declare(strict_types=1);

namespace App\Service;

use Chatkit\Chatkit;
use Chatkit\Exceptions\ChatkitException;
use Chatkit\Exceptions\MissingArgumentException;
use Chatkit\Exceptions\TypeMismatchException;

class ChatkitService
{
    /** @var Chatkit  */
    protected $chatkit;

    /**
     * @param string|null $instanceLocator
     * @param string|null $key
     * @throws MissingArgumentException
     */
    public function __construct(?string $instanceLocator, ?string $key)
    {
        $this->chatkit = new Chatkit([
            'instance_locator' => $instanceLocator,
            'key' => $key,
        ]);
    }

    /**
     * @param string $userId
     * @return array
     * @throws MissingArgumentException
     */
    public function authenticate(string $userId): array
    {
        return $this->chatkit->authenticate([
            'user_id' => $userId,
        ]);
    }

    /**
     * @param string $userId
     * @param string $name
     * @return array
     * @throws ChatkitException
     * @throws MissingArgumentException
     * @throws TypeMismatchException
     */
    public function createUser(string $userId, string $name): array
    {
        try{
            $response = $this->chatkit->createUser([
                'id' => $userId,
                'name' => $name
            ]);
        } catch(ChatkitException $exception) {
            if ( $exception->getMessage() === 'User with given id already exists' ) {
                return [
                    'status' => 200,
                    'body' => [
                        'id' => $userId,
                        'name' => $name,
                    ]
                ];
            }

            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $creatorId
     * @param string $name
     * @param array $userIds
     * @param bool $isPrivate
     * @return array
     * @throws MissingArgumentException
     */
    public function createRoom(string $creatorId, string $name, array $userIds = [], bool $isPrivate = false): array
    {
        return $this->chatkit->createRoom([
            'creator_id' => $creatorId,
            'name' => $name,
            'user_ids' => $userIds,
            'private' => $isPrivate,
        ]);
    }
}

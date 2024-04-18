<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ChatService
{
    /** @var ChatkitService */
    protected $chatkit;

    /** @var LegionfarmApiService */
    protected $legionfarmApi;

    /** @var string */
    protected $chatAdmin;

    public function __construct(
        ChatkitService $chatkitService,
        LegionfarmApiService $legionfarmApiService,
        string $chatAdmin
    ) {
        $this->chatkit = $chatkitService;
        $this->legionfarmApi = $legionfarmApiService;
        $this->chatAdmin = $chatAdmin;
    }

    /**
     * @param array $data
     * @return array
     */
    public function createRoom(array $data): array
    {
        if (!array_key_exists('executor_name', $data)) {
            throw new BadRequestHttpException('Parameter "executor_name" is required');
        }

        $executorName = $data['executor_name'];
        $executorChatId = $this->getChatId($executorName);

        if (!array_key_exists('client_name', $data)) {
            throw new BadRequestHttpException('Parameter "client_name" is required');
        }

        $clientName = $data['client_name'];
        $clientChatId = $this->getChatId($clientName);

        if (!array_key_exists('order_application_id', $data)) {
            throw new BadRequestHttpException('Parameter "order_application_id" is required');
        }

        $orderApplicationId = $data['order_application_id'];

        try {
            $this->chatkit->createUser($clientChatId, $clientName);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Cannot create client chat user');
        }

        try {
            $this->chatkit->createUser($executorChatId, $executorName);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Cannot create executor chat user');
        }

        try {
            $room = $this->chatkit->createRoom($this->getChatId($this->chatAdmin), 'Application order chat', [
                $clientChatId,
                $executorChatId,
            ]);
        } catch (Exception $exception) {
            throw new BadRequestHttpException('Cannot create chat room');
        }

        $this->legionfarmApi->updateOrderApplication([
            'id'      => $orderApplicationId,
            'room_id' => $room['body']['id'],
        ]);

        return $room;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getChatId(string $name): string
    {
        return md5('user{' . md5('%' . $name) . '}');
    }
}

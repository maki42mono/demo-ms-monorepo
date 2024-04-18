<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ChatkitService;
use App\Service\ChatService;
use App\Service\StreamChatService;
use GetStream\StreamChat\StreamException;
use Legion\LegionBundle\Entity\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ChatApiController
 * @Route("/back-api/order-chat/chat")
 */
class ChatApiController extends AbstractController
{
    /**
     * Create chat users if needed, create chat room and save chat room id to order application
     *
     * @Route("/create-room", methods={"POST"})
     *
     * @param Request $request
     * @param ChatService $chatService
     * @return ApiResponse
     */
    public function createRoom(Request $request, ChatService $chatService): ApiResponse
    {
        $data = $data = json_decode($request->getContent(), true, JSON_THROW_ON_ERROR);
        $response = $chatService->createRoom($data);

        return new ApiResponse("", $response);
    }

    /**
     * Get authenticate token to operate with Chatkit API
     *
     * @Route("/authenticate", methods={"POST"})
     *
     * @param Request $request
     * @param ChatkitService $chatkitService
     * @return ApiResponse
     */
    public function authenticate(Request $request, ChatkitService $chatkitService): ApiResponse
    {
        $userId = $request->get("user_id");
        if (empty($userId)) {
            throw new BadRequestHttpException('Parameter "user_id" is required');
        }

        $response = $chatkitService->authenticate($userId);

        return new ApiResponse("", $response['body']);
    }

    /**
     * @Route("/generate-token", methods={"GET"})
     *
     * @param Request $request
     * @param StreamChatService $streamChatService
     * @return ApiResponse
     * @throws StreamException
     */
    public function generateToken(Request $request, StreamChatService $streamChatService): ApiResponse
    {
        $userId = $request->get("user_id");
        if (empty($userId)) {
            throw new BadRequestHttpException('Parameter "user_id" is required');
        }

        $token = $streamChatService->generateToken($userId);

        return new ApiResponse("", ['token' => $token]);
    }

    /**
     * Get getstream settings
     *
     * @Route("/settings", methods={"GET"})
     * @return ApiResponse
     */
    public function settings(): ApiResponse
    {
        return new ApiResponse("", [
            'key'        => $this->getParameter('stream_chat_key'),
            'chat_admin' => $this->getParameter('lf_chat_admin'),
        ]);
    }
}

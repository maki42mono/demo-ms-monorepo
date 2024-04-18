<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\OrderService;
use Legion\LegionBundle\Entity\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * OrderController
 * @Route("/back-api/user/order")
 */
class OrderController extends AbstractController
{
    /* @var LoggerInterface */
    private $logger;

    /**
     * TestController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/list", methods={"GET"})
     * @param Request $request
     * @param OrderService $service
     * @return ApiResponse
     */
    public function list(Request $request, OrderService $service): ApiResponse
    {
        $this->logger->info("OrderController#list");

        $token = $request->headers->get("authorization");
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $email = $jwtPayload->email;

        $page = $request->get("page", null);
        $type = $request->get("type", "all");

        if (empty($email)) {
            throw new BadRequestHttpException("User email not found");
        }

        return new ApiResponse("", $service->getUsersOrders($email, $page, $type));
    }

    /**
     * @Route("/counts", methods={"GET"})
     * @param Request $request
     * @param OrderService $service
     * @return ApiResponse
     */
    public function counts(Request $request, OrderService $service): ApiResponse
    {
        $this->logger->info("OrderController#counts");

        $token = $request->headers->get("authorization");
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $email = $jwtPayload->email;

        if (empty($email)) {
            throw new BadRequestHttpException("User email not found");
        }

        return new ApiResponse("", $service->getUsersOrderCounts($email));
    }
}
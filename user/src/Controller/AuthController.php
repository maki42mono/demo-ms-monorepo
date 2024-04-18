<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AuthService;
use Legion\LegionBundle\Entity\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * AuthController
 * @Route("/back-api/user/auth")
 */
class AuthController extends AbstractController
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
     * @Route("/generate-otp", methods={"POST"})
     * @param Request $request
     * @param AuthService $service
     * @return ApiResponse
     */
    public function generateOtp(Request $request, AuthService $service): ApiResponse
    {
        $this->logger->info("AuthController#generateOtp");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (key_exists("role", $decodedBody)) {
            $result = $service->generateOtp($decodedBody["email"], $decodedBody["role"]);
        } else {
            $result = $service->generateOtp($decodedBody["email"]);
        }

        if (!$result) {
            throw new BadRequestHttpException();
        }

        return new ApiResponse(
            "OTP send to your email"
        );
    }

    /**
     * @Route("/token-link", methods={"POST"})
     * @param Request $request
     * @param AuthService $service
     * @return ApiResponse
     */
    public function generateTokenLink(Request $request, AuthService $service): ApiResponse
    {
        $this->logger->info("AuthController#generateTokenLink");

        $content = $request->getContent();
        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        $decodedBody = json_decode($content, true);

        if (!$service->generateTokenLink($decodedBody["email"])) {
            throw new BadRequestHttpException();
        }

        return new ApiResponse(
            "Link send to your email"
        );
    }
}
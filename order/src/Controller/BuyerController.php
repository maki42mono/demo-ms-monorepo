<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\BuyerInfoFormService;
use Legion\LegionBundle\Entity\ApiResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back-api/order/buyer")
 */
class BuyerController extends AbstractController
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
     * @Route("/info-form-params", methods={"GET"})
     * @param BuyerInfoFormService $service
     * @param Request $request
     * @return ApiResponse
     */
    public function infoFormParams(BuyerInfoFormService $service, Request $request): ApiResponse
    {
        return new ApiResponse("", $service->getParamsList($request->get("hash")));
    }

    /**
     * @Route("/send-info", methods={"POST"})
     * @param BuyerInfoFormService $service
     * @param Request $request
     * @return ApiResponse
     */
    public function sendInfo(BuyerInfoFormService $service, Request $request): ApiResponse
    {
        $this->logger->info("BuyerController#sendInfo");
        $content = $request->getContent();
        if (empty($content) || !$service->sendInfoForm(json_decode($content, true))) {
            throw new BadRequestHttpException();
        }

        return new ApiResponse();
    }
}
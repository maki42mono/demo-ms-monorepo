<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\OfferService;
use Legion\LegionBundle\Entity\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * OfferController
 * @Route("/back-api/user/offer")
 */
class OfferController extends AbstractController
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
     *
     * @Route("/list", methods={"GET"})
     * @SWG\Response(
     *      response="200",
     *      description="Get list of offers"
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="parentId",
     *     required=false,
     *     type="number",
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="expand",
     *     required=false,
     *     type="string"
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="domain",
     *     required=false,
     *     type="string",
     *     enum={"lf", "lfc"}
     * )
     *
     * @param Request $request
     * @param OfferService $service
     * @return ApiResponse
     */
    public function list(Request $request, OfferService $service): ApiResponse
    {
        $this->logger->info("OfferController#list");

        $params = $request->query->all();

        return new ApiResponse("", $service->getOffers($params));
    }

    /**
     *
     * @Route("/view", methods={"GET"})
     * @SWG\Response(
     *      response="200",
     *      description="Get list of offers"
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     required=true,
     *     type="number",
     * )
     * @SWG\Parameter(
     *     in="query",
     *     name="domain",
     *     required=false,
     *     type="string",
     *     enum={"lf", "lfc"}
     * )
     *
     * @param Request $request
     * @param OfferService $service
     * @return ApiResponse
     */
    public function view(Request $request, OfferService $service): ApiResponse
    {
        $this->logger->info("OfferController#view");

        $params = $request->query->all();

        return new ApiResponse("", $service->getOffer($params));
    }
}
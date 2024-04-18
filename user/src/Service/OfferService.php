<?php

namespace App\Service;

use Swagger\Annotations as SWG;

/**
 * @SWG\Schema(
 *   schema="OfferList",
 *   type="object",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     format="int32",
 *     description="category id"
 *  ),
 *  @OA\Property(
 *     property="title",
 *     type="string",
 *     description="title of category"
 *  ),
 *  @OA\Property(
 *     property="price",
 *     type="object",
 *     @OA\Property(
 *          property="usd",
 *          type="number",
 *          format="int32"
 *      ),
 *     @OA\Property(
 *          property="eur",
 *          type="number",
 *          format="int32"
 *      ),
 *     @OA\Property(
 *          property="ggt",
 *          type="number",
 *          format="int32"
 *      ),
 *     @OA\Property(
 *          property="oldCost",
 *          type="number",
 *          format="int32"
 *      ),
 *     @OA\Property(
 *          property="oldCostWithAdditionalPercent",
 *          type="number",
 *          format="int32"
 *      ),
 *  ),
 *     @OA\Property(
 *          property="image",
 *          type="string",
 *          description="url for image"
 *      ),
 *     @OA\Property(
 *          property="banner",
 *          type="string",
 *          description="url for image banner"
 *      ),
 *     @OA\Property(
 *          property="label",
 *          type="string",
 *          description="current offer label"
 *      ),
 *     @OA\Property(
 *          property="rating",
 *          type="number",
 *          format="float",
 *          description="current offer rating"
 *      ),
 *     @OA\Property(
 *          property="numberVoters",
 *          type="number",
 *          format="int32",
 *          description="current offer rating voters count"
 *      ),
 *     @OA\Property(
 *          property="estimate",
 *          type="number",
 *          format="int32",
 *          description="estimate in hours"
 *      ),
 *     @OA\Property(
 *          property="estimateStart",
 *          type="string",
 *          description="estimate start in hours"
 *      ),
 *
 * )
 *
 * @OA\Schema(
 *     schema="OfferView",
 *     allOf={
 *          @OA\Schema(ref="#/components/schemas/OfferList"),
 *          @OA\Schema(
 *              @OA\Property(
 *          property="seoDescription",
 *          type="string",
 *      ),
 *     @OA\Property(
 *          property="shortDescription",
 *          type="string",
 *      ),
 *     @OA\Property(
 *          property="serviceRequirementBlock",
 *          type="string",
 *      ),
 *     @OA\Property(
 *          property="whatGetOptions",
 *          type="array",
 *          @OA\Items(
 *              type="string",
 *          )
 *      ),
 *     @OA\Property(
 *          property="slider",
 *          type="object",
 *          @OA\Property(
 *              property="config",
 *              type="object",
 *          ),
 *          @OA\Property(
 *              property="title",
 *              type="string",
 *          ),
 *          @OA\Property(
 *              property="minInputTitle",
 *              type="string",
 *          ),
 *          @OA\Property(
 *              property="maxInputTitle",
 *              type="string",
 *          ),
 *      ),
 *     @OA\Property(
 *          property="trustPilot",
 *          type="object",
 *          @OA\Property(
 *              property="link",
 *              type="string",
 *          ),
 *          @OA\Property(
 *              property="reviewsCount",
 *              type="number",
 *              format="int32"
 *          ),
 *          @OA\Property(
 *              property="trustScore",
 *              type="string"
 *          ),
 *          @OA\Property(
 *              property="reviews",
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(
 *                      property="title",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="mainDescription",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="subtitle",
 *                      type="string"
 *                  ),
 *              )
 *          ),
 *      ),
 *     @OA\Property(
 *          property="additionalOptions",
 *          type="array",
 *          @OA\Items(
 *              type="object",
 *              @OA\Property(
 *                  property="id",
 *                  type="number",
 *                  format="int32"
 *              ),
 *              @OA\Property(
 *                  property="title",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="description",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="image",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="price",
 *                  type="object",
 *                  @OA\Property(
 *                      property="usd",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *                  @OA\Property(
 *                      property="eur",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *                  @OA\Property(
 *                      property="ggt",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *                  @OA\Property(
 *                      property="oldCost",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *                  @OA\Property(
 *                      property="costPercentage",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *                  @OA\Property(
 *                      property="costWithAdditional",
 *                      type="number",
 *                      format="int32"
 *                  ),
 *              ),
 *          )
 *      ),
 *          )
 *     }
 * )
 */


/**
 * Class OfferService
 * @package App\Service
 */
class OfferService
{
    private $YInfoFormService;

    public function __construct(YInfoFormService $YInfoFormService)
    {
        $this->YInfoFormService = $YInfoFormService;
    }

    /**
     * @param array $params
     * @return array|null
     */
    public function getOffers(array $params): ?array
    {
        return $this->YInfoFormService->getOffers($params);
    }

    /**
     * @param array $params
     * @return array|null
     */
    public function getOffer(array $params): ?array
    {
        return $this->YInfoFormService->getOffer($params);
    }
}
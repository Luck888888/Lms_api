<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\JsonResponse;
use Modules\Users\Policies\UserProfessionPolicy;
use Modules\Users\Services\UsersProfessionService;
use Modules\Users\Transformers\UserItemProfession;

class UsersProfessionController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/users/professions",
     *  tags={"Users"},
     *  summary="Get profession list.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Professions list.")),
     *           @OA\Schema(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserItemProfession"))),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     * )
     *
     * @param \Modules\Users\Services\UsersProfessionService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UsersProfessionService $service): JsonResponse
    {
        $this->authorize('show', UserProfessionPolicy::class);

        $professions = $service->getAll();

        return $this->sendSuccessResponse(
            __("Professions list."),
            UserItemProfession::collection($professions)
        );
    }
}

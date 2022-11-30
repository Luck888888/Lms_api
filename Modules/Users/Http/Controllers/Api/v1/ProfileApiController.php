<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Illuminate\Http\JsonResponse;
use Modules\Users\Http\Requests\UpdateProfileRequest;
use Modules\Users\Services\ProfileService;
use Modules\Users\Transformers\UserItem;

class ProfileApiController extends BaseApiController
{
    /**
     * @OA\Get(
     *  path="/profile",
     *  tags={"Profile"},
     *  summary="Get authenticated user profile.",
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Profile data.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UserItem")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     * Show the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(): JsonResponse
    {
        return $this->sendSuccessResponse(
            "Profile data.",
            new UserItem(auth()->user())
        );
    }

    /**
     * @OA\Patch(
     *  path="/profile",
     *  tags={"Profile"},
     *  summary="Update authenticated user profile.",
     *  security={{"bearerAuth":{}}},
     *  requestBody={"$ref": "#/components/requestBodies/UpdateProfileRequest"},
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Profile updated.")),
     *           @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/UserItem")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403"),
     *  @OA\Response(response=404, ref="#/components/responses/ErrorResponse404"),
     * )
     * Show the specified resource.
     *
     * @param \Modules\Users\Http\Requests\UpdateProfileRequest $request
     * @param \Modules\Users\Services\ProfileService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProfileRequest $request, ProfileService $service): JsonResponse
    {
        $user = $service->update($request->getProfileDto());

        return $this->sendSuccessResponse(
            "Profile updated.",
            new UserItem($user)
        );
    }
}

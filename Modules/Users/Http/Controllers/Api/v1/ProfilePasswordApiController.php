<?php

namespace Modules\Users\Http\Controllers\Api\v1;

use App\Http\Controllers\Base\BaseApiController;
use Modules\Users\Http\Requests\UpdateUserPasswordRequest;

class ProfilePasswordApiController extends BaseApiController
{

    /**
     * @OA\Patch(
     *  path="/profile/password",
     *  tags={"Profile"},
     *  summary="Update auth user password.",
     *  security={{"bearerAuth":{}}},
     *  requestBody={"$ref": "#/components/requestBodies/UpdateUserPasswordRequest"},
     *  @OA\Response(
     *      response=200, description="Successfull response.",
     *      @OA\JsonContent(allOf={
     *           @OA\Schema(ref="#/components/schemas/SuccessResponse"),
     *           @OA\Schema(@OA\Property(property="message", example="Profile password updated.")),
     *           @OA\Schema(@OA\Property(property="data", example="null")),
     *      })
     *  ),
     *  @OA\Response(response=400, ref="#/components/responses/ErrorResponse400"),
     *  @OA\Response(response=401, ref="#/components/responses/ErrorResponse401"),
     *  @OA\Response(response=403, ref="#/components/responses/ErrorResponse403")
     * )
     * Show the specified resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPasswordRequest $request)
    {
        auth()->user()->update(['password' => $request->password]);
        return $this->sendSuccessResponse(
            "Profile password updated."
        );
    }
}

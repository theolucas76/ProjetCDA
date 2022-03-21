<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\MaterialData;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\RequestBody(
 *     request="PostMaterialData",
 *     description="Post material data body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/PostMaterialDataRequest")
 *      )
 * )
 */

class MaterialDataController extends Controller
{
    /**
     * @OA\Post(
     *     path="/materials/data",
     *     summary="Post Material Data",
     *     description="Post a material data, only director",
     *     tags={"MaterialsData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(ref="#/components/requestBodies/PostMaterialData"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/MaterialData")
     *     ),
     *     @OA\Response(
     *          response="406",
     *          description="Error Not Acceptable",
     *          @OA\JsonContent(ref="#/components/schemas/NotAcceptableResponse")
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(ref="#/components/schemas/InternalServerErrorResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function postAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {

            $myDataMaterialId = ParameterHelper::testDataMaterialId($this, $request, $response, true);
            if ($myDataMaterialId === null) {
                return $response;
            }
            $myDataKey = ParameterHelper::testDataKey($this, $request, $response, true);
            if ($myDataKey === null) {
                return $response;
            }
            $myDataColumn = ParameterHelper::testDataColumn($this, $request, $response, true);
            if ($myDataColumn === null) {
                return $response;
            }

            $myData = new MaterialData();
            $myData->setDataMaterialId($myDataMaterialId);
            $myData->setDataKey($myDataKey);
            $myData->setDataColumn($myDataColumn);

            if (MaterialData::addMaterialData($myData)) {
                return $this->okResponse($response, $myData->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t add material data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }
}

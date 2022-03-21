<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaterialController extends Controller
{

    /**
     * @OA\Get(
     *     path="/materials/{materialId}",
     *     summary="Get Material by Id",
     *     description="Get material with material's data",
     *     tags={"Materials"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="materialId",
     *          description="Material id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/MaterialWithData")
     *          )
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param string $materialId
     * @return Response
     */
    public function getAction(Request $request, Response $response, string $materialId): Response
    {
        $myMaterial = Material::getMaterialById($materialId);
        if ($myMaterial === null) {
            return $this->notFoundResponse($response, 'material');
        }

        $myMaterialArray = $myMaterial->toArray();
        $myMaterialArray['data'] = array_map(static function (MaterialData $data): array {
            return $data->toArray();
        }, MaterialData::getMaterialDataByMaterial($myMaterial->getMaterialId()) );
        return $this->okResponse($response, $myMaterialArray);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Enums\MaterialCategory;
use App\Models\Enums\Role;
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
        }, MaterialData::getMaterialDataByMaterial($myMaterial->getMaterialId()));
        return $this->okResponse($response, $myMaterialArray);
    }

    /**
     * @OA\Get(
     *     path="/materials",
     *     summary="Get All Materials",
     *     tags={"Admin", "Materials"},
     *     description="Get all materials with data, only for director",
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="materials",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/MaterialWithData"),
     *                  minItems=2
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array( 'materials' => array_map(static function( Material $material) {
                $myMaterialArray = $material->toArray();
                $myMaterialArray['data'] = MaterialData::getMaterialDataByMaterial($material->getMaterialId());
                return $myMaterialArray;
            }, Material::getAllMaterials())));
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Get(
     *     path="/materials/byCategory/{categoryId}",
     *     summary="Get Materials by Category",
     *     description="Get materials with data by given category",
     *     tags={"Materials"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="categoryId",
     *          description="Category id",
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
     *          response="406",
     *          description="Error Not Acceptable",
     *          @OA\JsonContent(ref="#/components/schemas/NotAcceptableResponse")
     *     ),
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param string $category
     * @return Response
     */

    public function getsByCategory(Request $request, Response $response, string $categoryId): Response
    {
        if (MaterialCategory::hasKey($categoryId)) {
            return $this->okResponse($response, array('materials' => array_map(static function( Material  $material) {
                $myMaterialArray = $material->toArray();
                $myMaterialArray['data'] = MaterialData::getMaterialDataByMaterial($material->getMaterialId());
                return$myMaterialArray;
            }, Material::getMaterialByCategory($categoryId))));
        }
        return $this->notAcceptableResponse($response, 'category');
    }

//    public function postAction(Request $request, Response $response): Response
//    {
//        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
//
//        }
//        return $this->unauthorizedResponse($response, 'only director');
//    }
}

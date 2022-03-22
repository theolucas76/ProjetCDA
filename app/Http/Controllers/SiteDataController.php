<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\Site;
use App\Models\SiteData;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\RequestBody(
 *     request="PostSiteData",
 *     description="Post Site data body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/PostSiteDataRequest")
 *      )
 * )
 */
/**
 * @OA\RequestBody(
 *     request="UpdateSiteData",
 *     description="Update Site data body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/UpdateSiteDataRequest")
 *      )
 * )
 */

class SiteDataController extends Controller
{

    /**
     * @OA\Get(
     *     path="/sites/data/{dataId}",
     *     summary="Get SiteData By Id",
     *     tags={"SitesData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="dataId",
     *          description="SiteData id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/SiteData")
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
     * @param string $dataId
     * @return Response
     */
    public function getAction(Request $request, Response $response, string $dataId): Response
    {
        $mySiteData = SiteData::getSiteDataById($dataId);
        if ($mySiteData === null) {
            return $this->notFoundResponse($response, 'site data');
        }
        return $this->okResponse($response, $mySiteData->toArray());
    }


    /**
     * @OA\Get(
     *     path="/sites/data/all",
     *     summary="Get All SiteData ",
     *     tags={"SitesData", "Admin"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="siteData",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/SiteData"),
     *                  minItems=2
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array( 'siteData' => array_map(static function(SiteData $siteData): array {
                return $siteData->toArray();
            }, SiteData::getAllSiteData())));
        }
        return $this->unauthorizedResponse($response, 'only director');
    }


    /**
     * @OA\Get(
     *     path="/sites/data/site/{siteId}",
     *     summary="Get SiteData By Site",
     *     tags={"SitesData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="siteId",
     *          description="Site id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="siteData",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/SiteData"),
     *                  minItems=2
     *              )
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
     *
     * @param Request $request
     * @param Response $response
     * @param string $siteId
     * @return Response
     */
    public function getsBySiteAction(Request $request, Response $response, string $siteId): Response
    {
        $mySite = Site::getSiteById($siteId);
        if ($mySite === null) {
            return $this->notFoundResponse($response, 'site');
        }
        return $this->okResponse($response, array( 'siteData' => array_map(static function(SiteData $data): array {
            return $data->toArray();
        }, SiteData::getSiteDataBySite($siteId))));
    }

    /**
     * @OA\Post(
     *      path="/sites/data",
     *     summary="Post Site Data",
     *     description="Post a site data, only director",
     *     tags={"SitesData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(ref="#/components/requestBodies/PostSiteData"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/SiteData")
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
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
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function postAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myDataSiteId = ParameterHelper::testDataSiteId($this, $request, $response, true);
            if ($myDataSiteId === null) {
                return $this->notAcceptableResponse($response, 'data_site_id');
            }
            if (Site::getSiteById($myDataSiteId) === null) {
                return $this->notFoundResponse($response, 'site');
            }
            $myDataKey = ParameterHelper::testDataKey($this, $request, $response, true);
            if ($myDataKey === null) {
                return $this->notAcceptableResponse($response, 'data_key');
            }
            $myDataColumn = ParameterHelper::testDataColumn($this, $request, $response, true);
            if ($myDataColumn === null) {
                return $this->notAcceptableResponse($response, 'data_column');
            }

            $mySiteData = new SiteData();
            $mySiteData->setSiteId($myDataSiteId);
            $mySiteData->setDataKey($myDataKey);
            $mySiteData->setDataColumn($myDataColumn);

            if (SiteData::addSiteData($mySiteData)) {
                return $this->okResponse($response, $mySiteData->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t add site data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Put(
     *     path="/sites/data/update",
     *     summary="Update SiteData",
     *     description="Update SiteData with SiteData Model in body",
     *     security={{ "apiAuth": {} }},
     *     tags={"SitesData"},
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateSiteData"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/SiteData")
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
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
    public function putAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myDataId = $this->getParam($request, 'data_id');
            $mySiteData = SiteData::getSiteDataById($myDataId);
            if ($mySiteData === null) {
                return $this->notFoundResponse($response, 'site data');
            }
            $myDataKey = ParameterHelper::testDataKey($this, $request, $response, false);
            if ($myDataKey !== null) {
                $mySiteData->setDataKey($myDataKey);
            }
            $myDataColumn = ParameterHelper::testDataColumn($this, $request, $response, false);
            if ($myDataColumn !== null) {
                $mySiteData->setDataColumn($myDataColumn);
            }
            if (SiteData::updateSiteData($mySiteData)) {
                return $this->okResponse($response, $mySiteData->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t update site data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Delete(
     *     path="/sites/data/delete/{dataId}",
     *     summary="Delete SiteData",
     *     tags={"SitesData"},
     *     description="Delete a SiteData",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="dataId",
     *          description="SiteData Id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success"
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
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
     * @param Request $request
     * @param Response $response
     * @param string $dataId
     * @return Response
     */
    public function deleteAction(Request $request, Response $response, string $dataId): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $mySiteData = SiteData::getSiteDataById($dataId);
            if ($mySiteData === null) {
                return $this->notFoundResponse($response, 'site data');
            }
            if (SiteData::deleteSiteData($dataId)) {
                return $this->okResponse($response);
            }
            return $this->internalServerErrorResponse($response, 'Can\'t delete SiteData');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

}

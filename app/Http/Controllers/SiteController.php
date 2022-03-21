<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\SiteData;
use App\Models\Utils\Functions;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="PostSite",
 *      description="Post Site body",
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/PostSiteRequest")
 *      )
 *  )
 */
class SiteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/sites/{siteId}",
     *     summary="Get Site By Id",
     *     tags={"Sites"},
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
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/SiteWithData")
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

    public function getAction(Request $request, Response $response, string $siteId): Response
    {
        $mySite = Site::getSiteById($siteId);
        if ($mySite === null) {
            return $this->notFoundResponse($response, 'site');
        }
        $mySiteDatas = SiteData::getSiteDataBySite($mySite->getId());
        return $this->okResponse($response, array(
            'site' => $mySite->toArray(),
            'data' => array_map(static function (SiteData $data): array {
                return $data->toArray();
            }, $mySiteDatas)));
    }

    /**
     *
     * @OA\Get(
     *     path="/sites/user/{userId}",
     *     summary="Get Site By User",
     *     tags={"Sites"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/SiteWithData")
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
     * @param string $userId
     * @return Response
     */

    public function getsActionByUser(Request $request, Response $response, string $userId): Response
    {
        return $this->okResponse($response, array('sites' => array_map(static function (Site $site): array {
            $mySiteArray = $site->toArray();
            $mySiteData = SiteData::getSiteDataBySite($site->getId());
            $mySiteArray['data'] = $mySiteData;
            return $mySiteArray;
        }, Site::getSiteByUser($userId))));
    }


    /**
     *
     * @OA\Post(
     *     path="/sites",
     *     summary="Post a Site",
     *     tags={"Sites"},
     *     description="Post a Site",
     *     @OA\RequestBody(ref="#/components/requestBodies/PostSite"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/Site")
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function postAction(Request $request, Response $response): Response
    {
        $myNumberSite = ParameterHelper::testNumberSite($this, $request, $response, true);
        if ($myNumberSite === null) {
            return $response;
        }

        $myDateStart = Functions::fromUnix(ParameterHelper::testSiteDateStart($this, $request, $response, false) ?? time());
        if ($myDateStart === null) {
            return $response;
        }

        $myDateEnd = Functions::fromUnix(ParameterHelper::testSiteDateEnd($this, $request, $response, true));
        if ($myDateEnd === null) {
            return $response;
        }

        $mySite = new Site();
        $mySite->setNumberSite($myNumberSite);
        $mySite->setDateStart($myDateStart);
        $mySite->setDateEnd($myDateEnd);

        if (Site::addSite($mySite)) {
            return $this->okResponse($response, $mySite->toArray());
        }
        return $this->internalServerErrorResponse($response, 'Can\'t add site');
    }
}

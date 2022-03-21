<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\Site;
use App\Models\SiteData;
use App\Models\Utils\Functions;
use App\Models\Utils\ParameterHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use function Sodium\add;

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

/**
 * @OA\RequestBody(
 *     request="UpdateSite",
 *     description="Update Site body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/UpdateSiteRequest")
 *     )
 * )
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
        $mySiteArray = $mySite->toArray();
        $mySiteArray['data'] = array_map(static function (SiteData $data): array {
            return $data->toArray();
        }, SiteData::getSiteDataBySite($mySite->getId()) );
        return $this->okResponse($response, $mySiteArray);
    }

    /**
     * @OA\Get(
     *     path="/sites",
     *     summary="Get All Sites",
     *     tags={"Admin", "Sites"},
     *     security={{ "apiAuth": {} }},
     *     description="Get all sites and site's data, only for director",
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="sites",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/SiteWithData"),
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
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array( 'sites' => array_map(static function (Site $site): array {
                $mySiteArray = $site->toArray();
                $mySiteData = SiteData::getSiteDataBySite($site->getId());
                $mySiteArray['data'] = $mySiteData;
                return $mySiteArray;
            }, Site::getAllSites()) ));
        }
        return $this->unauthorizedResponse($response, 'only director');
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
     * @OA\Get(
     *     path="/sites/year/{year}",
     *     summary="Get sites by year",
     *     description="Get all sites by given year",
     *     tags={"Sites"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="year",
     *          description="Year 'YYYY'",
     *          required=true,
     *          example="2022",
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
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @param string $year
     * @return Response
     * @throws Exception
     */
    public function getByYearAction(Request $request, Response $response, string $year): Response
    {
        $startYear = new \DateTime('01/01/' . $year);
        $endYear = new \DateTime('01/01/' . $year);
        $endYear->add(new \DateInterval('P1Y'));

        if (is_int($startYear->getTimestamp())) {
            return $this->okResponse($response, array('sites' => array_map(static function (Site $site) {
                $mySiteArray = $site->toArray();
                $mySiteArray['data'] = SiteData::getSiteDataBySite($site->getId());
                return $mySiteArray;
            }, Site::getSitesByYear($startYear->getTimestamp(), $endYear->getTimestamp())) ));
        }
        return $this->notAcceptableResponse($response, 'year');

    }


    /**
     * @OA\Get(
     *     path="/count/sites",
     *     summary="Count of sites",
     *     tags={"Counts"},
     *     description="Number of sites in database",
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="sites_count",
     *                  type="integer",
     *                  default=12
     *              )
     *          )
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function getCountAction(Request $request, Response $response): Response
    {
        return $this->okResponse($response, array( 'sites_count' => count(Site::getAllSites()) ));
    }

    /**
     * @OA\Get(
     *     path="/count/currentSites",
     *     summary="Count of current sites",
     *     description="Number of current sites",
     *     tags={"Counts"},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="current_count",
     *                  type="integer",
     *                  default=12
     *              )
     *          )
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function getCurrentCountAction(Request $request, Response $response): Response
    {
        return $this->okResponse($response, array( 'current_count' => count(Site::getCurrentSites()) ));
    }

    /**
     * @OA\Get(
     *     path="/count/previousSites",
     *     summary="Count of previous sites",
     *     description="Number of previous sites",
     *     tags={"Counts"},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="previous_count",
     *                  type="integer",
     *                  default=12
     *              )
     *          )
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getPreviousCountAction(Request $request, Response $response): Response
    {
        return $this->okResponse($response, array( 'previous_count' => count(Site::getPreviousSites()) ));
    }

    /**
     * @OA\Get(
     *     path="/count/sitesByYear/{year}",
     *     summary="Count sites in the year",
     *     tags={"Counts"},
     *     description="Number of sites in the given year",
     *     @OA\Parameter(
     *          name="year",
     *          description="Year of the site",
     *          required=true,
     *          example="2022",
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="year_count",
     *                  type="integer",
     *                  default=12
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="406",
     *          description="Error Not Acceptable",
     *          @OA\JsonContent(ref="#/components/schemas/NotAcceptableResponse")
     *     ),
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @param string $year
     * @return Response
     * @throws Exception
     */
    public function getCountByYearAction(Request $request, Response $response, string $year): Response
    {
        $startYear = new \DateTime('01/01/' . $year);
        $endYear = new \DateTime('01/01/' . $year);
        $endYear->add(new \DateInterval('P1Y'));

        if (is_int($startYear->getTimestamp())) {
            return $this->okResponse($response, array( 'year_count' => count(Site::getSitesByYear($startYear->getTimestamp(), $endYear->getTimestamp())) ));
        }
        return $this->notAcceptableResponse($response, 'year');
    }


    /**
     *
     * @OA\Post(
     *     path="/sites",
     *     summary="Post a Site",
     *     tags={"Sites"},
     *     description="Post a Site",
     *     security={{ "apiAuth": {} }},
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

        if (Site::getSiteByNumberSite($myNumberSite) !== null) {
            return $this->notAcceptableResponse($response, 'number site exist');
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

    /**
     * @OA\Put(
     *     path="/sites",
     *     summary="Update Site",
     *     description="Update Site with Site Model in body",
     *     security={{ "apiAuth": {} }},
     *     tags={"Sites"},
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateSite"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/Site")
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function putAction(Request $request, Response $response): Response
    {
        $mySiteId = $this->getParam($request, 'site_id');
        $mySite = Site::getSiteById($mySiteId);
        if ($mySite === null) {
            return $this->notFoundResponse($response, 'site');
        }

        $myNumberSite = ParameterHelper::testNumberSite($this, $request, $response, false);
        if ($myNumberSite !== null) {
            $numberSite = Site::getSiteByNumberSite($myNumberSite);
            if ($numberSite !== null && $numberSite->getId() === $mySite->getId()) {
                $mySite->setNumberSite($myNumberSite);
            }elseif ($numberSite === null) {
                $mySite->setNumberSite($myNumberSite);
            }else {
                return $this->notAcceptableResponse($response, 'number site exist');
            }

        }

        $myDateStart = Functions::fromUnix(ParameterHelper::testSiteDateStart($this, $request, $response, false));
        if ($myDateStart !== null) {
            $mySite->setDateStart($myDateStart);
        }

        $myDateEnd = Functions::fromUnix(ParameterHelper::testSiteDateEnd($this, $request, $response, false));
        if ($myDateEnd !== null) {
            $mySite->setDateEnd($myDateEnd);
        }

        if (Site::updateSite($mySite)) {
            return $this->okResponse($response, $mySite->toArray());
        }
        return $this->internalServerErrorResponse($response, 'Can\'t update site');
    }

    /**
     * @OA\Delete(
     *     path="/sites/delete/{siteId}",
     *     summary="Delete Site",
     *     tags={"Sites"},
     *     description="Delete a Site, update site.deleted_at",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="siteId",
     *          description="Site Id",
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
     *
     * @param Request $request
     * @param Response $response
     * @param string $siteId
     * @return Response
     */

    public function deleteAction(Request $request, Response $response, string $siteId): Response {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $mySite = Site::getSiteById($siteId);
            if ($mySite === null) {
                return $this->notFoundResponse($response, 'site');
            }
            if (Site::deleteSite($mySite)) {
                return $this->okResponse($response);
            }
            return $this->internalServerErrorResponse($response, 'Can\'t remove site');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

}

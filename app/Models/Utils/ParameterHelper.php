<?php

namespace App\Models\Utils;

use App\Http\Controllers\Controller;
use App\Models\Enums\Job;
use App\Models\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ParameterHelper
{
    private static function testRegex(Controller $controller, Request $request, Response $response, string $var, string $regex, bool $required): ?string
    {
        $myValue = $controller->getParam($request, $var);
        if ($myValue === null ) {
            if ($required) {
                $response = $controller->notAcceptableResponse($response, $var);
            }
            return null;
        }
        if (preg_match($regex, $myValue)) {
            return $myValue;
        }
        $response = $controller->notAcceptableResponse($response, $var . 'as valid');
        return null;
    }

    private static function testFunction(Controller $controller, Request $request, Response $response, string $var, callable $callable, bool $required): ?string
    {
        $myValue = $controller->getParam($request, $var);
        if ($myValue === null) {
            if ($required) {
                $response = $controller->notAcceptableResponse($response, $var);
            }
            return null;
        }
        if ($callable($myValue)) {
            return $myValue;
        }
        $response = $controller->notAcceptableResponse($response, $var . ' as valid');
        return null;
    }

    private static function testEnum(Controller $controller, Request $request, Response &$response, string $var, string $enumClass, bool $required)
    {
        $myValue = self::testFunction($controller, $request, $response, $var, $enumClass . '::hasKey', $required);

        if ($myValue !== null) {
            $myFunction = $enumClass . '::get';
            return $myFunction($myValue);
        }
        return null;
    }

    private static function testBoolean(Controller $controller, Request $request, Response &$response, string $var, bool $required): ?bool
    {
        return self::testFunction($controller, $request, $response, $var, 'is_bool', $required);
    }

    private static function testInt(Controller $controller, Request $request, Response &$response, string $var, bool $required) : ?int
    {
        $myValue = ParameterHelper::testRegex( $controller, $request, $response, $var, '/^[0-9]+$/', $required );
        if ($myValue !== null) {
            return (int)$myValue;
        }
        return null;
    }

    private static function testString(Controller $controller, Request $request, Response &$response, string $var, bool $required): ?string
    {
        $myValue = ParameterHelper::testFunction($controller, $request, $response, $var, 'is_string', $required);
        if ($myValue !== null) {
            return $myValue;
        }
        return null;
    }

    public static function testRole(Controller $controller, Request $request, Response &$response, bool $required): ?Role
    {
        $myValue = ParameterHelper::testEnum($controller, $request, $response, 'role', Role::class, $required);
        if ($myValue !== null) {
            return $myValue;
        }
        return null;
    }

    public static function testJob(Controller $controller, Request $request, Response &$response, bool $required): ?Job
    {
        $myValue = ParameterHelper::testEnum($controller, $request, $response, 'job', Job::class, $required);
        if ($myValue !== null) {
            return $myValue;
        }
        return null;
    }

    public static function testLogin(Controller $controller, Request $request, Response &$response, bool $required): ?string
    {
        return ParameterHelper::testString($controller, $request, $response, 'login', $required);
    }

    public static function testPassword(Controller $controller, Request $request, Response $response, bool $required): ?string
    {
        return ParameterHelper::testFunction($controller, $request, $response, 'password', function( string $password ) {
            return preg_match( '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,64}/', $password );
        }, $required);

    }

    public static function testNumberSite(Controller $controller, Request $request, Response $response, bool $required): ?int
    {
        return ParameterHelper::testInt($controller, $request, $response, 'site_number_site', $required);
    }

    public static function testSiteDateStart(Controller $controller, Request $request, Response $response, bool $required): ?int
    {
        return ParameterHelper::testInt($controller, $request, $response, 'site_date_start', $required);
    }

    public static function testSiteDateEnd(Controller $controller, Request $request, Response $response, bool $required): ?int
    {
        return ParameterHelper::testInt($controller, $request, $response, 'site_date_end', $required);
    }

}

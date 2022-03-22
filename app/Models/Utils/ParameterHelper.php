<?php

namespace App\Models\Utils;

use App\Http\Controllers\Controller;
use App\Models\Enums\Job;
use App\Models\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ParameterHelper
{
    /**
     * Function qui test la variable $var avec la regex $regex
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param string $regex
     * @param bool $required
     * @return string|null
     */
    private static function testRegex(Controller $controller, Request $request, Response $response, string $var, string $regex, bool $required): ?string
    {
        // Récupération de la valeur de $var dans la request $request, si null return not acceptable response
        $myValue = $controller->getParam($request, $var);
        if ($myValue === null ) {
            if ($required) {
                $response = $controller->notAcceptableResponse($response, $var);
            }
            return null;
        }
        // Test de la valeur récupérée avec la regex donnée et retourne la valeur si ok
        if (preg_match($regex, $myValue)) {
            return $myValue;
        }
        $response = $controller->notAcceptableResponse($response, $var . 'as valid');
        return null;
    }

    /**
     * Function qui test la variable $var avec une function de callback $callable(ex: is_string())
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param callable $callable
     * @param bool $required
     * @return string|null
     */
    private static function testFunction(Controller $controller, Request $request, Response $response, string $var, callable $callable, bool $required): ?string
    {
        // Récupération de la valeur de $var dans la request $request, si null return not acceptable response
        $myValue = $controller->getParam($request, $var);
        if ($myValue === null) {
            if ($required) {
                $response = $controller->notAcceptableResponse($response, $var);
            }
            return null;
        }
        // Test de la valeur récupéré avec la function donnée, retourne la valeur si ok
        if ($callable($myValue)) {
            return $myValue;
        }
        $response = $controller->notAcceptableResponse($response, $var . ' as valid');
        return null;
    }

    /**
     * Function qui test si la variable $var est compris dans la class d'enum $enumClass
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param string $enumClass
     * @param bool $required
     * @return mixed|null
     */
    private static function testEnum(Controller $controller, Request $request, Response &$response, string $var, string $enumClass, bool $required)
    {
        // Test si $var(key) est compris dans l'enum avec la function hasKey(key) de AbstractEnum (extends dans chaque Enum)
        $myValue = self::testFunction($controller, $request, $response, $var, $enumClass . '::hasKey', $required);

        // Return la valeur de l'enum avec la function get(key)
        if ($myValue !== null) {
            $myFunction = $enumClass . '::get';
            return $myFunction($myValue);
        }
        return null;
    }

    /**
     * Test si $var est bool return null si non
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param bool $required
     * @return bool|null
     */
    private static function testBoolean(Controller $controller, Request $request, Response &$response, string $var, bool $required): ?bool
    {
        return self::testFunction($controller, $request, $response, $var, 'is_bool', $required);
    }

    /**
     * Test si $var est un int
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param bool $required
     * @return int|null
     */
    private static function testInt(Controller $controller, Request $request, Response &$response, string $var, bool $required) : ?int
    {
        $myValue = ParameterHelper::testRegex( $controller, $request, $response, $var, '/^[0-9]+$/', $required );
        if ($myValue !== null) {
            return (int)$myValue;
        }
        return null;
    }

    /**
     * Test si $var est un string
     * @param Controller $controller
     * @param Request $request
     * @param Response $response
     * @param string $var
     * @param bool $required
     * @return string|null
     */
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

    public static function testDataMaterialId(Controller $controller, Request $request, Response $response, bool $required): ?int
    {
        return ParameterHelper::testInt($controller, $request, $response, 'data_material_id', $required);
    }

    public static function testDataKey(Controller $controller, Request $request, Response $response, bool $required): ?string
    {
        return ParameterHelper::testString($controller, $request, $response, 'data_key', $required);
    }

    public static function testDataColumn(Controller $controller, Request $request, Response $response, bool $required): ?string
    {
        return ParameterHelper::testString($controller, $request, $response, 'data_column', $required);
    }

    public static function testMaterialName(Controller $controller, Request $request, Response $response, bool $required): ?string
    {
        return ParameterHelper::testString($controller, $request, $response, 'material_name', $required);
    }

    public static function testDataSiteId(Controller $controller, Request $request, Response $response, bool $required): ?int
    {
        return ParameterHelper::testInt($controller, $request, $response, 'data_site_id', $required);
    }
}

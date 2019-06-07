<?php
/**
 * LLR Technologies & Associated Services
 * Information Systems Development
 *
 * Mercury Merlot
 *
 * User: lromero
 * Date: 4/07/2019
 * Time: 4:48 PM
 */


namespace utilities;

/**
 * Class URIParser
 *
 * Uses configuration settings to generate the requested URI
 *
 * @package utilities
 */
class URIParser
{
    /**
     * @return array
     */
    public static function getURIParts(): array
    {
        // Create string of URL requested by browser
        if(isset($_SERVER['HTTPS']))
            $requestedURL = "https";
        else
            $requestedURL = "http";

        $requestedURL .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Produce final resource identifier
        $uri = strtolower(rtrim(explode(\Config::OPTIONS['baseURL'] . \Config::OPTIONS['baseURI'], explode('?', $requestedURL)[0])[1], "/"));

        return explode('/', $uri);
    }
}
<?php
/**
 * LLR Technologies & Associated Services
 * Information Systems Development
 *
 * ISD MERCURY DOORWAY
 *
 * URL Shortener
 *
 * User: lromero
 * Date: 6/07/2019
 * Time: 9:49 AM
 */


namespace controllers;


use utilities\InfoCentralConnection;

class FrontController
{
    public static function processRequest(): void
    {
        try
        {
            $url = $_SERVER['HTTP_HOST']; // The domain
            $uri = $_SERVER['REQUEST_URI']; // Part after domain

            $alias = rtrim(explode(\Config::OPTIONS['baseURL'], $url)[0], '.');

            $results = InfoCentralConnection::getResponse(InfoCentralConnection::POST, 'urlaliases/search', array(
                'alias' => $alias
            ))->getBody();

            foreach($results as $result)
            {
                if($result['alias'] == $alias)
                {
                    header('Location: ' . rtrim($result['destination'], '/') . $uri );
                    exit;
                }
            }

            die('Alias Not Found');
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
        }
    }
}
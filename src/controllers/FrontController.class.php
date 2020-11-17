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


use Config;
use database\DatabaseConnection;
use utilities\InfoCentralConnection;

class FrontController
{
    public static function processRequest(): void
    {
        try
        {
            $url = $_SERVER['HTTP_HOST']; // The domain
            $uri = $_SERVER['REQUEST_URI']; // Part after domain

            $alias = rtrim(explode(Config::OPTIONS['baseURL'], $url)[0], '.');

            // Log this request if feature is enabled
            if(Config::OPTIONS['enableLogging'])
            {
                $database = new DatabaseConnection();

                $record = $database->prepare("INSERT INTO `NIS_URLAliasHit` VALUES (:alias, :uri, NOW(), :ipAddress)");
                $record->bindParam('alias', $alias, DatabaseConnection::PARAM_STR);
                $record->bindParam('uri', $uri, DatabaseConnection::PARAM_STR);
                $record->bindParam('ipAddress', $_SERVER['REMOTE_ADDR'], DatabaseConnection::PARAM_STR);
                $record->execute();

                $database->close();
            }

            $results = InfoCentralConnection::getResponse(InfoCentralConnection::POST, 'urlaliases/search', array(
                'alias' => $alias
            ))->getBody();

            foreach($results as $result)
            {
                if($result['alias'] == $alias)
                {
                    if(strpos($result['destination'], ';') !== FALSE)
                    {
                        // MULTI-MARK
                        $script = '<script>';
                        $destinations = explode(';', $result['destination']);

                        foreach($destinations as $destination)
                        {
                            $script .= "window.open('{$destination}', '_blank');\n";
                        }

                        // Replace current window with last link
                        $script .= '</script>';

                        die($script . "<h1>Navigation Complete!</h1><p>Allow this window to open new tabs if prompted, otherwise you may close this window</p>");
                    }

                    // Single URL Redirect (with URI preserved)
                    header('Location: ' . rtrim(rtrim($result['destination'], '/') . $uri, '/'));
                    exit;
                }
            }

            http_response_code(404);
            die("<h1>Domain '$alias' Not Found</h1>");
        }
        catch(\Exception $e)
        {
            die($e->getMessage());
        }
    }
}
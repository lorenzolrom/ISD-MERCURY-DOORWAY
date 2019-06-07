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
 * Time: 9:39 AM
 */

spl_autoload_register(
    function($className)
    {
        /** @noinspection PhpIncludeInspection */
        require_once('../' . str_replace("\\", "/", $className) . ".class.php");
    }
);

\controllers\FrontController::processRequest();
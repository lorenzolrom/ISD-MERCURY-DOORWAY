<?php
/**
 * LLR Technologies & Associated Services
 * Information Systems Development
 *
 * Mercury Merlot
 *
 * User: lromero
 * Date: 4/07/2019
 * Time: 3:31 PM
 */


namespace utilities;


use exceptions\InfoCentralException;
use models\HTTPResponse;

class InfoCentralConnection
{
    const GET = 0;
    const POST = 1;
    const PUT = 2;
    const DELETE = 3;

    /**
     * @param int $requestType
     * @param string $resource
     * @param array $data
     * @return HTTPResponse
     * @throws InfoCentralException
     */
    public static function getResponse(int $requestType, string $resource, array $data = array()): HTTPResponse
    {
        $link = curl_init(\Config::OPTIONS['icURL'] . $resource);

        switch($requestType)
        {
            case self::POST:
                $type = "POST";
                break;
            case self::PUT:
                $type = "PUT";
                break;
            case self::DELETE:
                $type = "DELETE";
                break;
            default:
                $type = "GET";
        }

        // Add API secret
        $headers = array(
            'Secret: ' . \Config::OPTIONS['icSecret']
        );

        // Add the user's token if it has been defined
        if(isset($_COOKIE[\Config::OPTIONS['cookieName']]))
            $headers[] = 'Token: ' . $_COOKIE[\Config::OPTIONS['cookieName']];

        curl_setopt($link, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($link, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($link, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($link, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($link);
        $responseCode = curl_getinfo($link, CURLINFO_HTTP_CODE);
        curl_close($link);

        if($responseCode == 500)
            throw new InfoCentralException(InfoCentralException::MESSAGES[InfoCentralException::IC_RESPONDED_500], InfoCentralException::IC_RESPONDED_500);

        if(!is_array($data = json_decode($response, TRUE)))
            $data = array();

        return new HTTPResponse($responseCode, $data);
    }
}
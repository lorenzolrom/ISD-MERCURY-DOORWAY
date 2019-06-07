<?php
/**
 * LLR Technologies & Associated Services
 * Information Systems Development
 *
 * Mercury Merlot
 *
 * User: lromero
 * Date: 4/07/2019
 * Time: 3:44 PM
 */


namespace exceptions;


class InfoCentralException extends MercuryException
{
    const IC_RESPONDED_500 = 1300;

    const MESSAGES = array(
        self::IC_RESPONDED_500 => 'The InfoCentral server did not provide a valid response'
    );
}
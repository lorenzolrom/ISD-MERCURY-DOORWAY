<?php
/**
 * LLR Technologies & Associated Services
 * Information Systems Development
 *
 * Mercury Merlot
 *
 * User: lromero
 * Date: 3/23/2019
 * Time: 9:18 AM
 */


namespace exceptions;


class EntryNotFoundException extends MercuryException
{
    const PRIMARY_KEY_NOT_FOUND = 301;
    const UNIQUE_KEY_NOT_FOUND = 302;

    const MESSAGES = array(
        self::PRIMARY_KEY_NOT_FOUND => "Record not found",
        self::UNIQUE_KEY_NOT_FOUND => "Record not found"
    );
}
<?php
namespace App\Utils;

/**
 * Class HttpCode - use for http response
 * Full list http codes https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 * @package App\Utils
 */
class HttpCode
{
    //200
    const OK = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;

    //400
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    //500
    const INTERNAL_SERVER_ERROR = 500;
}
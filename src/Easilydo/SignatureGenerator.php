<?php
namespace Easilydo;

class SignatureGenerator
{
    const HASH_ALGORITHM = 'sha1';

    /**
     * Generates a HMAC-SHA1 hash based on the steps described here:
     * https://sift.easilydo.com/sift/documentation#signature-generation
     *
     * @param string $apiSecret The API secret obtained from sift developer console
     * @param string $method GET/POST/PUT/DELETE
     * @param string $path The path of the request (e.g. /v1/discovery)
     * @param array $params A combination of the query parameters and the request body (if present)
     *
     * @return string A HMAC-SHA1 hash
     */
    public static function generate($apiSecret, $method, $path, array $params = [])
    {
        $paramsString = self::generateParamsString($params);
        $sig = "$method&$path$paramsString";
        return hash_hmac(self::HASH_ALGORITHM, $sig, $apiSecret);
    }

    /**
     * Generates an alphabetically sorted string of params as described here:
     * https://sift.easilydo.com/sift/documentation#signature-generation
     *
     * @param array $paramsArray A combination of the query parameters and the request body (if present)
     *
     * @return string An alphabetically sorted string of params
     */
    private static function generateParamsString($paramsArray)
    {
        ksort($paramsArray);

        $baseString = '';
        foreach ($paramsArray as $key => $value) {
            $baseString .= "&$key=$value";
        }

        return $baseString;
    }
}
?>

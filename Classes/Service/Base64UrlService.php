<?php

namespace Carbon\Eel\Service;

use InvalidArgumentException;
use function base64_decode;
use function base64_encode;
use function rtrim;
use function strtr;

class Base64UrlService
{
    /**
     * Encode data to Base64 URL format
     *
     * @param string $data The data to encode
     * @param bool $padding If true, the "=" padding at end of the encoded value are kept, else it is removed
     * @return string The data encoded
     */
    public static function encode(string $data, bool $padding = false): string
    {
        $encoded = strtr(base64_encode($data), '+/', '-_');

        return $padding === true ? $encoded : rtrim($encoded, '=');
    }

    /**
     * Decode data from Base64 URL format
     *
     * @param string $data The data to decode
     * @throws InvalidArgumentException
     * @return string The data decoded
     */
    public static function decode(string $data): string
    {
        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        if ($decoded === false) {
            throw new InvalidArgumentException('Invalid data provided');
        }

        return $decoded;
    }
}

<?php

namespace PhpProbe\Adapter;

/**
 * Class PhpCurlAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class PhpCurlAdapter implements AdapterInterface
{
    /**
     * Check a resource's HTTP status over HTTP/HTTPS
     *
     * @param array $parameters
     *
     * @throws \RuntimeException
     * @return bool|string
     */
    public function check(array $parameters)
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('PHP curl extension is not installed');
        }

        $curlHandler = curl_init();

        curl_setopt($curlHandler, CURLOPT_URL, $parameters['url']);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, isset($parameters['contains']) ? 1 : 0);
        curl_setopt($curlHandler, CURLOPT_HEADER, 0);

        ob_start();
        $content = curl_exec($curlHandler);
        $httpCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);
        ob_end_clean();

        if ($httpCode != $parameters['expectedHttpCode']) {
            return sprintf("Expected HTTP code %d, got %d", $parameters['expectedHttpCode'], $httpCode);
        }

        if (isset($parameters['contains']) && !mb_strpos($content, $parameters['contains'])) {
            return sprintf("Expected content '%s' not found in response.", $parameters['contains']);
        }

        return true;
    }
}

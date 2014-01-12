<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\HttpAdapterResponse;
use PhpProbe\Helper\AdapterHelper;

/**
 * Class PhpCurlAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 *
 * @codeCoverageIgnore
 */
class PhpCurlAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * Check a resource's HTTP status over HTTP/HTTPS with PHP's Curl functions
     *
     * @param array $parameters
     *
     * @throws \RuntimeException
     * @return $this
     */
    public function check(array $parameters)
    {
        AdapterHelper::checkPhpExtension('curl');

        $timerStart  = microtime();
        $curlHandler = curl_init();

        curl_setopt($curlHandler, CURLOPT_URL, $parameters['url']);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandler, CURLOPT_HEADER, 0);

        ob_start();
        $content  = curl_exec($curlHandler);
        $httpCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);
        ob_end_clean();
        $timerEnd = microtime();
        $duration = $timerEnd - $timerStart;

        $response = new HttpAdapterResponse(
            array(
                'content'  => $content,
                'httpCode' => $httpCode
            )
        );

        if ($content === false) {
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
            $response->setError(sprintf("Unable to reach URL '%s'", $parameters['url']));
        } else {
            $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
            $response->setResponseTime($duration);
        }
        $this->setResponse($response);

        return $this;
    }
}

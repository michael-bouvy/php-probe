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

        $curlHandler = curl_init();

        curl_setopt($curlHandler, CURLOPT_URL, $parameters['url']);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, isset($parameters['contains']) ? 1 : 0);
        curl_setopt($curlHandler, CURLOPT_HEADER, 0);

        ob_start();
        $content  = curl_exec($curlHandler);
        $httpCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        curl_close($curlHandler);
        ob_end_clean();

        $response = new HttpAdapterResponse(
            array(
                'content'  => $content,
                'httpCode' => $httpCode
            )
        );
        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        $this->setResponse($response);

        return $this;
    }
}

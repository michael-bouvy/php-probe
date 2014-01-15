<?php

namespace PhpProbe\Adapter\Response;

/**
 * Class HttpAdapterResponse
 *
 * Following properties can be defined for this response adapter :
 * - content  : response content
 * - httpCode : response HTTP code
 *
 * @method void     setContent($content)
 * @method void     setHttpCode($httpCode)
 * @method void     setResponseTime($responseTime)
 * @method string   getContent()
 * @method int      getHttpCode()
 * @method double   getResponseTime()
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Response
 */
class HttpAdapterResponse extends AbstractAdapterResponse
{
}

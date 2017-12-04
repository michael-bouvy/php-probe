<?php

namespace PhpProbe\Adapter\Response;

/**
 * Class DatabaseAdapterResponse
 *
 * Following properties can be defined for this response adapter :
 * - databases : array containing all available databases
 * - result: query result
 *
 * @method void   setDatabases($databases)
 * @method array  getDatabases()
 * @method void   setResult($result)
 * @method mixed  getResult()
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Response
 */
class DatabaseAdapterResponse extends AbstractAdapterResponse
{
}

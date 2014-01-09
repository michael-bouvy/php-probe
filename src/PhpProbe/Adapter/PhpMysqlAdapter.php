<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\DatabaseAdapterResponse;
use PhpProbe\Helper\AdapterHelper;

/**
 * Class PhpMysqlAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class PhpMysqlAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * Check connection to a MySQL database using PHP's Mysql functions
     *
     * @param array $parameters
     *
     * @throws \RuntimeException
     * @return $this
     */
    public function check(array $parameters)
    {
        AdapterHelper::checkPhpExtension('mysql');

        $response = new DatabaseAdapterResponse();

        if (false === $connection = mysql_connect(
            $parameters['host'],
            $parameters['user'],
            $parameters['password']
        )
        ) {
            $error = sprintf("Connection problem : %s", mysql_error());
            $response->setError($error);
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
        } else {
            $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        }

        if (isset($parameters['database']) && mysql_select_db($parameters['database'], $connection)) {
            $response->setDatabaseExists(true);
        }

        $this->setResponse($response);

        return $this;
    }
}

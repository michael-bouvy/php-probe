<?php

namespace PhpProbe\Adapter;

/**
 * Class PhpMysqlAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class PhpMysqlAdapter implements AdapterInterface
{
    /**
     * Always return true
     *
     * @param array $parameters
     *
     * @throws \RuntimeException
     * @return bool
     */
    public function check(array $parameters)
    {
        if (!extension_loaded('mysql')) {
            throw new \RuntimeException('PHP mysql extension is not installed');
        }

        if (false === $connection = mysql_connect(
            $parameters['host'],
            $parameters['user'],
            $parameters['password']
        )) {
            return sprintf("Connection problem : %s", mysql_error());
        }

        if (isset($parameters['database']) && !mysql_select_db($parameters['database'], $connection)) {
            return sprintf("Database '%s' not found.", $parameters['database']);
        }

        return true;
    }
}

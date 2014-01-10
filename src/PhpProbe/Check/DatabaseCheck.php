<?php
namespace PhpProbe\Check;

use PhpProbe\Adapter\Reponse\DatabaseAdapterResponse;

/**
 * Class DatabaseCheck
 *
 * @author  Julien CHICHIGNOUD <julien.chichignoud@gmail.com>
 * @package PhpProbe\Check
 */
class DatabaseCheck extends AbstractCheck
{
    /**
     * Check if a specific database exists
     *
     * @param DatabaseAdapterResponse $response
     * @param string                  $database
     *
     * @return mixed
     */
    protected function checkDatabase(DatabaseAdapterResponse $response, $database)
    {
        if ($response->getDatabaseExists() !== true) {
            return sprintf("Database '%s' not found.", $database);
        }

        return true;
    }
}

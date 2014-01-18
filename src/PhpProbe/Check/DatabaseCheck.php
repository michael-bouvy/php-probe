<?php
namespace PhpProbe\Check;

use PhpProbe\Adapter\Response\DatabaseAdapterResponse;

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
     * @param string|array            $database
     *
     * @return string|boolean
     */
    protected function checkDatabase(DatabaseAdapterResponse $response, $database)
    {
        if (is_array($database)) {
            $diff = array_diff($database, $response->getDatabases());
            if (count($diff)) {
                return sprintf("Databases '%s' not found.", implode(", ", $diff));
            }
        }

        if (!is_array($database) && !in_array($database, $response->getDatabases())) {
            return sprintf("Database '%s' not found.", $database);
        }

        return true;
    }
}

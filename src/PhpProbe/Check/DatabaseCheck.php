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
        } elseif (!in_array($database, $response->getDatabases())) {
            return sprintf("Database '%s' not found.", $database);
        }

        return true;
    }

    /**
     * Check for query result
     *
     * @param DatabaseAdapterResponse $response
     * @param mixed                   $expectedResult Expected query result
     *
     * @return string|boolean
     */
    protected function checkQuery(DatabaseAdapterResponse $response, $expectedResult)
    {
        $result = $response->getResult();

        if ($result != $expectedResult) {
            return sprintf('Unexpected query result [%s] (expected [%s])', $result, $expectedResult);
        }

        return true;
    }
}

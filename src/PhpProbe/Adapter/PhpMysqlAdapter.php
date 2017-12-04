<?php

namespace PhpProbe\Adapter;

use PDO;
use PhpProbe\Adapter\Response\AdapterResponseInterface;
use PhpProbe\Adapter\Response\DatabaseAdapterResponse;
use PhpProbe\Helper\AdapterHelper;

/**
 * Class PhpMysqlAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 *
 * @codeCoverageIgnore
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
        AdapterHelper::checkPhpExtension('pdo_mysql');

        $response = new DatabaseAdapterResponse();

        $dsn = sprintf('mysql:host=%s', $parameters['host']);

        try {
            $dbh = new PDO(
              $dsn,
              $parameters['user'],
              $parameters['password']
            );
        } catch (\Exception $e) {
            $error = sprintf("Connection problem : %s", $e->getMessage());
            $response->setError($error);
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
            $this->setResponse($response);

            return $this;
        }

        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $databases = array();
        foreach ($dbh->query('show databases') as $row) {
            $databases[] = $row['Database'];
        }
        $response->setDatabases($databases);

        if (isset($parameters['query'])) {
            $query = $dbh->query($parameters['query']);
            if ($query === false) {
                $response->setError(implode(', ', $dbh->errorInfo()));
                $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
                $this->setResponse($response);

                return $this;
            }

            $column = $query->fetchColumn();
            if ($column) {
                $response->setResult($column);
            }
        }

        $this->setResponse($response);

        return $this;
    }
}

<?php

namespace PhpProbe\Check;

use \PhpProbe\Adapter\Reponse\AbstractAdapterResponse;

/**
 * Interface CheckInterface
 *
 * @author  Julien CHICHIGNOUD <julien.chichignoud@gmail.com>
 * @package PhpProbe\Check
 */
interface CheckInterface
{
    /**
     * Check a response against the defined criteria
     *
     * @param AbstractAdapterResponse $response
     *
     * @return array
     */
    public function check(AbstractAdapterResponse $response);
}

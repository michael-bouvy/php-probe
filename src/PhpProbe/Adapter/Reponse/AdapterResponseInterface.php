<?php

namespace PhpProbe\Adapter\Reponse;

/**
 * Class AdapterResponseInterface
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Reponse
 */
interface AdapterResponseInterface
{
    const STATUS_SUCCESSFUL = 'success';
    const STATUS_FAILED     = 'failed';
    const STATUS_UNKNOWN    = 'unknown';

    /**
     * This method should return true when adapter ran successfuly.
     *
     * For instance, this may return true if a connection could be established.
     *
     * @return bool
     */
    public function isSuccessful();
}

<?php

namespace PhpProbe;

use PhpProbe\Probe\ProbeInterface;

/**
 * Class Manager
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe
 */
class Manager
{
    /**
     * @var ProbeInterface[]
     */
    protected $probes = array();

    /**
     * @var int
     */
    protected $failCount = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Check all probes
     *
     * @return $this
     */
    public function checkAll()
    {
        foreach ($this->probes as $probe) {
            $probe->check();
            if ($probe->hasFailed()) {
                $this->failCount++;
            }
        }
        return $this;
    }

    /**
     * Terminate probing
     *
     * @return void
     */
    public function end()
    {
        if (php_sapi_name() == 'cli') {
            if ($this->failCount > 0) {
                exit(1);
            } else {
                exit(0);
            }
        }
    }

    /**
     * Output result of probes in plain text
     *
     * @param bool $includeSuccess Include success messages in output or not
     * @param bool $httpHeader     Send HTTP headers
     *
     * @return $this
     */
    public function outputText($includeSuccess = false, $httpHeader = true)
    {
        $output = '';
        foreach ($this->probes as $probe) {
            if ($probe->hasFailed()) {
                $output .= "# " . $probe->getName() . " - Failure (" . $probe->getErrorMessage() . ")\n";
            } else {
                if ($includeSuccess === true) {
                    $output .= "# " . $probe->getName() . " - Success\n";
                }
            }
        }

        if ($httpHeader === true) {
            $this->setHttpHeader();
        }

        print $output;

        return $this;
    }

    /**
     * Output result of probes in plain text
     *
     * @param bool $includeSuccess Include success messages in output or not
     * @param bool $httpHeader     Send HTTP headers
     *
     * @return $this
     */
    public function outputHtml($includeSuccess = false, $httpHeader = true)
    {
        $htmlOutput = '<ul>';
        foreach ($this->probes as $probe) {
            if ($probe->hasFailed()) {
                $htmlOutput .= '<li>' . $probe->getName() . ' - Failure (' . $probe->getErrorMessage() . ') </li>';
            } else {
                if ($includeSuccess === true) {
                    $htmlOutput .= '<li>' . $probe->getName() . ' - Success</li>';
                }
            }
        }
        $htmlOutput .= '</ul>';

        if ($httpHeader === true) {
            $this->setHttpHeader();
        }

        print $htmlOutput;

        return $this;
    }

    /**
     * Set HTTP headers according to probes' results
     *
     * @return void
     */
    public function setHttpHeader()
    {
        if (php_sapi_name() != 'cli') {
            if ($this->failCount > 0) {
                header("Cache-Control: no-cache, max-age=0");
                header("HTTP/1.1 503 Service Unavailable", null, 503);
            } else {
                header("Cache-Control: no-cache, max-age=0");
            }
        }
    }

    /**
     * Add a probe to check
     *
     * @param ProbeInterface $probe
     *
     * @return $this
     */
    public function addProbe(ProbeInterface $probe)
    {
        $this->probes[$probe->getHash()] = $probe;
        return $this;
    }

    /**
     * Get all probes
     *
     * @return Probe\ProbeInterface[]
     */
    public function getProbes()
    {
        return $this->probes;
    }
}

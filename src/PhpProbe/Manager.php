<?php

namespace PhpProbe;

use PhpProbe\Exception\ExitException;
use PhpProbe\Helper\CliHelper;
use PhpProbe\Helper\HttpHelper;
use PhpProbe\Helper\ProbeHelper;
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
    public $failCount = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        set_exception_handler(
            CliHelper::getExitExceptionHandler()
        );
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
     * @throws Exception\ExitException
     * @return void
     */
    public function end()
    {
        if (php_sapi_name() == 'cli') {
            if ($this->hasFailures()) {
                throw new ExitException('Success', 1);
            } else {
                throw new ExitException('Failure', 0);
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

        if ($httpHeader === true && $this->hasFailures()) {
            HttpHelper::setFailHttpHeader();
        } elseif ($httpHeader === true && !$this->hasFailures()) {
            HttpHelper::setSuccessHttpHeader();
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

        if ($httpHeader === true && $this->hasFailures()) {
            HttpHelper::setFailHttpHeader();
        } elseif ($httpHeader === true && !$this->hasFailures()) {
            HttpHelper::setSuccessHttpHeader();
        }

        print $htmlOutput;

        return $this;
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

    /**
     * @param string $fileName Config filename
     * @param null   $parsingLibrary
     */
    public function importConfig($fileName, $parsingLibrary = null)
    {
        if (is_null($parsingLibrary)) {
            $parsingLibrary = new \Symfony\Component\Yaml\Yaml;
        }

        $parsedFile = $parsingLibrary::parse($fileName);
        $probes     = $parsedFile['probes'];

        foreach ($probes as $probeName => $probe) {
            $className = ProbeHelper::getClassNameFromType($probe['type']);
            if (class_exists($className)) {
                $probe = new $className($probeName, $probe['options']);
                $this->addProbe($probe);
            }
        }
    }

    /**
     * Check if there have been any probe failure
     *
     * @return int
     */
    public function hasFailures()
    {
        return ($this->failCount > 0);
    }
}

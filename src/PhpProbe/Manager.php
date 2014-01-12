<?php

namespace PhpProbe;

use PhpProbe\Check\CheckInterface;
use PhpProbe\Exception\ExitException;
use PhpProbe\Helper\AdapterHelper;
use PhpProbe\Helper\CheckHelper;
use PhpProbe\Helper\CliHelper;
use PhpProbe\Helper\HttpHelper;
use PhpProbe\Helper\ProbeHelper;
use PhpProbe\Probe\ProbeInterface;
use Symfony\Component\Yaml\Yaml;

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
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

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
                $output .= "# " . $probe->getName() . " - Failure (";
                $output .= implode(" - ", $probe->getErrorMessages());
                $output .= ")\n";
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
                $htmlOutput .= '<li>' . $probe->getName() . ' - Failure (';
                $htmlOutput .= implode(" - ", $probe->getErrorMessages());
                $htmlOutput .= ') </li>';
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
     *
     * @throws \RuntimeException
     */
    public function importConfig($fileName, $parsingLibrary = null)
    {
        if (is_null($parsingLibrary)) {
            $parsingLibrary = new Yaml;
        }

        if (!file_exists($fileName)) {
            throw new \RuntimeException(sprintf("File '%s' does not exist.", $fileName));
        }

        $parsedFile = $parsingLibrary::parse($fileName);
        $this->importProbesFromParsedFile($parsedFile);
    }

    /**
     * Import probes from the parsed content of a config file
     *
     * @param array $parsedFile Parsed content of config file
     *
     * @return void
     */
    public function importProbesFromParsedFile($parsedFile)
    {
        foreach ($parsedFile['probes'] as $probeName => $probe) {
            $className = ProbeHelper::getClassNameFromType($probe['type']);

            /* @var ProbeInterface $probeInstance */
            $probeInstance = new $className($probeName, $probe['options']);

            if (isset($probe['adapter'])) {
                $adapterClass = AdapterHelper::getClassNameFromType($probe['adapter']);
                $adapter      = new $adapterClass();
                $probeInstance->setAdapter($adapter);
            }

            if (isset($probe['checkers'])) {
                foreach ($probe['checkers'] as $checkerName => $checker) {
                    $checkerClass    = CheckHelper::getClassNameFromType(ucfirst($checkerName));
                    /** @var CheckInterface $checkerInstance */
                    $checkerInstance = new $checkerClass();
                    foreach ($checker as $type => $param) {
                        $checkerInstance->addCriterion($type, $param);
                    }

                    $probeInstance->addChecker($checkerInstance);
                }
            }

            $this->addProbe($probeInstance);
        }
    }

    /**
     * Check if there have been any probe failure
     *
     * @return bool
     */
    public function hasFailures()
    {
        return ($this->failCount > 0);
    }
}

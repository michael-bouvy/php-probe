<?php
foreach ($probes as $probe) {
    if ($probe->hasFailed()) {
        print "# " . $probe->getName() . " - Failure (";
        print implode(" - ", $probe->getErrorMessages());
        print ")\n";
    } else {
        if ($includeSuccess === true) {
            print "# " . $probe->getName() . " - Success\n";
        }
    }
}

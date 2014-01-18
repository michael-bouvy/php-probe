<?php
print "Test template\n";
foreach ($probes as $probe) {
    if ($probe->hasFailed()) {
        print "# " . $probe->getName() . " - Failure (";
        print implode(" - ", $probe->getErrorMessages());
        print ")\n";
    } elseif ($probe->hasPartiallySucceeded()) {
        print "# " . $probe->getName() . " - Warning (";
        print implode(" - ", $probe->getErrorMessages());
        print ")\n";
    } else {
        if ($includeSuccess === true) {
            print "# " . $probe->getName() . " - Success\n";
        }
    }
}

<?php
print '<ul>';
foreach ($probes as $probe) {
    if ($probe->hasFailed()) {
        print '<li>' . $probe->getName() . ' - Failure (';
        print implode(" - ", $probe->getErrorMessages());
        print ') </li>';
    } elseif ($probe->hasPartiallySucceeded()) {
        print '<li>' . $probe->getName() . ' - Warning (';
        print implode(" - ", $probe->getErrorMessages());
        print ') </li>';
    } else {
        if ($includeSuccess === true) {
            print '<li>' . $probe->getName() . ' - Success</li>';
        }
    }
}
print '</ul>';

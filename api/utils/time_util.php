<?php

function getEpochWeek($time)
{
    // number of weeks since jan 1970
    // normalised to monday as the start of the week (so +3)
    // one week off since +3
    return intval((($time / (86400 * 1000)) + 3) / 7);
}

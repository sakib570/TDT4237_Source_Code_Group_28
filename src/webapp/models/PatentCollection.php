<?php

namespace tdt4237\webapp\models;

use ArrayObject;

class PatentCollection extends ArrayObject
{

    public function sortByDate()
    {
        $this->uasort(function (Patent $a, Patent $b) {
            return strcmp($a->getDate(), $b->getDate());
        });
    }
}

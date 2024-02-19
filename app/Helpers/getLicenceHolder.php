<?php

/**
 * Retrieves the name of the licence holder based on the provided licence.
 *
 * @param object $licence The licence object to retrieve the holder's name from.
 * @return string The name of the licence holder.
 */
function getLicenceHolder($licence){
    if($licence->belongs_to == 'Company'){
        return $licence?->name;
    }else if($licence->belongs_to == 'Individual'){
        return $licence?->full_name;
    }
    return '';
}


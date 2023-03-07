<?php ?>
<?php
function areValuesSet(...$values)
{
    $valuesAreSet = true;
    foreach ($values as $value) {
        if (!isset($value)) {
            $valuesAreSet = false;
        }
    }
    return $valuesAreSet;
}

function areValuesNotEmpty(...$values)
{
    $valuesAreNotEmpty = true;
    foreach ($values as $value) {
        if (empty($value)) {
            $valuesAreNotEmpty = false;
        }
    }
    return $valuesAreNotEmpty;
}
//
?>
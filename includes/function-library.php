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

function checkStringValidy($string, $pattern="/^.+$/", $minlength = 0, $maxlength=PHP_INT_MAX) {
    if($minlength<=strlen($string)&&strlen($string)<=$maxlength){
        return preg_match($pattern, $string);
    }else{
        return false;
    }
}

function checkPasswordIsRight($inputedPass, $hashedPass){
    if(password_verify($inputedPass, $hashedPass)){
        return true;
    }else{
        return false;
    } 
}

?>
<?php ?>
<?php

function checkStringValidy($string, $pattern="/^.+$/", $minlength = 1, $maxlength=PHP_INT_MAX) {
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
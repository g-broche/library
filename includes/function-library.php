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

function getUserIdForPassRecovery($dbco, $email, $phone){
    try{
        $sqlSelect='SELECT id FROM tblreaders WHERE EmailId=:email AND MobileNumber=:phoneNumber';

        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':email',$email);
        $statement->bindParam(':phoneNumber',$phone);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Si le resultat de recherche n'est pas vide
        if(!empty($result)){
            return $result[0]['id'];
        }else{
            return 0;
        }
    } catch (err) {
        return -1;
    }
}

function updateUserPassword($dbco, $id, $pass){
    try{
        $sqlUpdate = "UPDATE tblreaders SET `Password`=:password WHERE id=:userId";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlUpdate);
        $statement->bindParam(':password',$pass);
        $statement->bindParam(':userId',$id);
        $statement->execute();
        if($statement->rowCount()>0){
            return true;
        }else{
            return false;
        }
    }catch (err) {
        return false;
    }
}
?>
<?php
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

function getBookLentAmount($dbco, $readerId){
    try{
        $sqlUpdate = "SELECT BookId FROM tblissuedbookdetails WHERE ReaderID=:readerId";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlUpdate);
        $statement->bindParam(':readerId',$readerId);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        $bookCount =0;
        foreach($result as $rows){
            $bookCount++;
        }
        return $bookCount;
    }catch (err) {
        return -1;
    }
}

function getBookNotReturned($dbco, $readerId){
    try{
        $sqlUpdate = "SELECT BookId FROM tblissuedbookdetails WHERE ReaderID=:readerId AND ReturnStatus=0";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlUpdate);
        $statement->bindParam(':readerId',$readerId);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        $bookCount =0;
        foreach($result as $rows){
            $bookCount++;
        }
        return $bookCount;
    }catch (err) {
        return -1;
    }
} ?>
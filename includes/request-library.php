<?php

function getUser($dbco, $userMail){
    try{
        $sql = "SELECT EmailId, Password, ReaderId, Status FROM tblreaders  WHERE EmailId = :email";
        $query = $dbco->prepare($sql);
        $query->bindParam(':email', $userMail);
        // On execute la requete
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if(!empty($result)){
            return $result[0];
        }else{
            return null;
        }
    }catch (error){
        return null;
    }
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
    } catch (error) {
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
    }catch (error) {
        return false;
    }
} 

function getBookLentAmount($dbco, $readerId){
    try{
        $sqlSelect = "SELECT BookId FROM tblissuedbookdetails WHERE ReaderID=:readerId";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':readerId',$readerId);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        $bookCount = count($result);
        return $bookCount;
    }catch (error) {
        return -1;
    }
}

function getBookNotReturned($dbco, $readerId){
    try{
        $sqlSelect = "SELECT BookId FROM tblissuedbookdetails WHERE ReaderID=:readerId AND ReturnStatus=0";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':readerId',$readerId);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        $bookCount = count($result);
        return $bookCount;
    }catch (error) {
        return -1;
    }
} 

function selectAdmin($dbco, $adminLogin){
    try{
        $sqlSelect = "SELECT Password, FullName FROM admin WHERE UserName=:userName";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':userName',$adminLogin);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)==1){
            return $result;
        }else if (count($result)==0){
            return 0;
        }else{
            return -1;
        }
    }catch (error) {
        return -1;
    }
}

function selectUser($dbco, $userMail){
    try{
        $sqlSelect = "SELECT * FROM tblreaders WHERE EmailId=:userMail";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':userMail',$userMail);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)==1){
            return $result[0];
        }else if (count($result)==0){
            return -1;
        }else{
            return -1;
        }
    }catch (error) {
        return -1;
    }
}

function updateUser($dbco, $userId, $userName, $userPhone, $userEmail){
    try {
        $sqlUpdate = "UPDATE tblreaders SET FullName=:name, EmailId=:email, MobileNumber=:phone WHERE ReaderId=:userId";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlUpdate);
        $statement->bindParam(':name',$userName);
        $statement->bindParam(':phone',$userPhone);
        $statement->bindParam(':email',$userEmail);
        $statement->bindParam(':userId',$userId);
        $statement->execute();
        if($statement->rowCount()>0){
            return [1, $userEmail];
        }else{
            return [-1, null];
        }
    } catch (error) {
        return -1;
    }
}

function getIssuedBooksHistory($dbco, $userId){
    try{
        $sqlSelect = "SELECT tblbooks.BookName,tblbooks.ISBNNumber, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate, tblissuedbookdetails.ReturnStatus  FROM `tblbooks` JOIN `tblissuedbookdetails` ON tblbooks.id=tblissuedbookdetails.BookId AND ReaderID=:readerId";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $dbco->prepare($sqlSelect);
        $statement->bindParam(':readerId',$userId);
        $statement->execute();
        $result =  $statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($result)){
            return [0, NULL];
        }else{
            return [1, $result];
        }
    }catch(error){
        return [-1, NULL];
    }
}

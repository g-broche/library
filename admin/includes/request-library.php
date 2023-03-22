<?php

function countAvailableBooks($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblbooks";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}
function countLentBooks($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblissuedbookdetails WHERE ReturnStatus=0";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}
function countReturnedBooks($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblissuedbookdetails WHERE ReturnStatus=1";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}
function countReaders($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblreaders";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}
function countAuthors($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblauthors";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}
function countCategories($dbco){
    try{
        $sql = "SELECT COUNT(id) FROM tblcategory";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbco->prepare($sql);
        $query->execute();
        // On stocke le resultat de recherche dans une variable $result
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]['COUNT(id)']];
        }else{
            return [0, 'aucun résultat'];
        }
    }catch (err){
        return [-1, 'erreur base de donnée'];
    }
}

function addNewCategory($dbco, $name, $status){
    try{
        $sql="INSERT INTO tblcategory (CategoryName, Status) SELECT :name , :status WHERE NOT EXISTS (SELECT 1 FROM tblcategory WHERE CategoryName=:name)";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':status', $status);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function getCategories($dbco){
    try{
        $sql="SELECT * FROM tblcategory" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}

function getCategorie($dbco, $id){
    try{
        $sql="SELECT * FROM tblcategory WHERE id=:catId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':catId', $id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}

function disableCategory($dbco, $id){
    try{
        $sql="UPDATE tblcategory SET Status=0 WHERE id=:catId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':catId', $id);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){

        return -1;
    }
}

function updateCategory($dbco, $id, $name, $status){
    try{
        $sql="UPDATE tblcategory SET CategoryName=:name, Status=:newStatus WHERE id=:catId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':catId', $id);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':newStatus', $status);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){

        return -1;
    }
}

function addNewAuthor($dbco, $name){
    try{
        $sql="INSERT INTO tblauthors (AuthorName) SELECT :name WHERE NOT EXISTS (SELECT 1 FROM tblauthors WHERE AuthorName=:name)";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':name', $name);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function getAuthors($dbco){
    try{
        $sql="SELECT * FROM tblauthors" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}

function disableAuthor($dbco, $id){
    try{
        $sql="UPDATE tblauthors SET Status=0 WHERE id=:Id" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':Id', $id);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){

        return -1;
    }
}

function getAuthor($dbco, $id){
    try{
        $sql="SELECT * FROM tblauthors WHERE id=:authorId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':authorId', $id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}

function updateAuthor($dbco, $id, $name, $status){
    try{
        $sql="UPDATE tblauthors SET AuthorName=:name, Status=:newStatus WHERE id=:authorId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':authorId', $id);
        $statement->bindParam(':name', $name);
        $statement->bindParam(':newStatus', $status);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){

        return -1;
    }
}

function addNewBook($dbco, $title, $category, $author, $isbn, $price){
    try{
        $sql="INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice, Status) SELECT :title , :cat, :author, :isbn, :price, 1 WHERE NOT EXISTS (SELECT 1 FROM tblbooks WHERE ISBNNumber=:isbn)";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':cat', $category, PDO::PARAM_INT);
        $statement->bindParam(':author', $author, PDO::PARAM_INT);
        $statement->bindParam(':isbn', $isbn, PDO::PARAM_INT);
        $statement->bindParam(':price', $price);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function getBooksInfos($dbco){
    try{
            $sql="SELECT tblbooks.id, tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice FROM tblbooks JOIN tblcategory ON tblbooks.CatId=tblcategory.id JOIN tblauthors ON tblbooks.AuthorId=tblauthors.id";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}
function getABookInfos($dbco, $id){
    try{
        $sql="SELECT tblbooks.id, tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice FROM tblbooks JOIN tblcategory ON tblbooks.CatId=tblcategory.id JOIN tblauthors ON tblbooks.AuthorId=tblauthors.id AND tblbooks.id=:id";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($result)){
            return [1, $result[0]];
        }else{
            return [0, NULL];
        }
    }catch (err){
        return [-1, NULL];
    }
}

function deleteBook($dbco, $id){
    try{
        $sql="DELETE FROM tblbooks WHERE id=:id" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){

        return -1;
    }
}

function updateBook($dbco, $id, $title, $CatId, $authorId, $ISBNNumber, $BookPrice){
    try{
        $sql="UPDATE tblbooks SET BookName=:title, CatId=:catid, AuthorId=:authId, ISBNNumber=:ISBN, BookPrice=:price WHERE id=:bookId" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':title', $title);
        $statement->bindParam(':catid', $CatId ,PDO::PARAM_INT);
        $statement->bindParam(':authId', $authorId, PDO::PARAM_INT);
        $statement->bindParam(':ISBN', $ISBNNumber, PDO::PARAM_INT);
        $statement->bindParam(':price', $BookPrice);
        $statement->bindParam(':bookId', $id);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function insertIssuedBook($dbco, $readerSID, $BookISBN){
    try{
        $sql="INSERT INTO tblissuedbookdetails (BookId, ReaderID, ReturnStatus) SELECT tb.id, tr.ReaderId, 0 FROM tblbooks AS tb CROSS JOIN tblreaders AS tr WHERE tr.ReaderID=:readerID AND tb.ISBNNumber=:isbn";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':readerID', $readerSID);
        $statement->bindParam(':isbn', $BookISBN, PDO::PARAM_INT);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function getAllIssuedInfos($dbco){
    try{
        $sql="SELECT tib.id, tr.FullName, tb.BookName, tb.ISBNNumber, tib.IssuesDate, tib.ReturnDate, tib.ReturnStatus FROM tblissuedbookdetails AS tib JOIN tblreaders AS tr ON tib.ReaderID=tr.ReaderId JOIN tblbooks AS tb ON tib.BookId=tb.id";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($result!==null){
            return [1,$result] ;
        }else{
            return [0,null];
        }
    }catch (err){
        return [-1,null];
    }
}
function getSpecificIssued($dbco, $issueID){
    try{
        $sql="SELECT tib.id, tr.FullName, tb.BookName, tb.ISBNNumber, tib.IssuesDate, tib.ReturnDate, tib.ReturnStatus FROM tblissuedbookdetails AS tib JOIN tblreaders AS tr ON tib.ReaderID=tr.ReaderId JOIN tblbooks AS tb ON tib.BookId=tb.id WHERE tib.id=:issueID";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':issueID', $issueID, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($result!==null){
            return [1,$result[0]] ;
        }else{
            return [0,null];
        }
    }catch (err){
        return [-1,null];
    }
}

function setIssuedReturned($dbco, $issuedId){
    try{
        $sql="UPDATE tblissuedbookdetails SET ReturnStatus=1 WHERE id=:id" ;
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->bindParam(':id', $issuedId ,PDO::PARAM_INT);
        $statement->execute();
        if($statement->rowCount()>0){
            return 1;
        }else{
            return 0;
        }
    }catch (err){
        return -1;
    }
}

function getAllUsers($dbco){
    try{
        $sql="SELECT id, FullName, ReaderId, EmailId, MobileNumber, RegDate, Status  FROM tblreaders";
        $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement =$dbco->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($result!==null){
            return [1,$result] ;
        }else{
            return [0,null];
        }
    }catch (err){
        return [-1,null];
    }
}



?>
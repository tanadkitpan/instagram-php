<?php

namespace InstagramScraper;

class connectdatabase {

    public static function getInstance() {
        $instance = new self();
        return $instance;
    }

    public static function insertPhotoCode($owner, $username, $IDALL) {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
        
        for ($i = 0; $i < sizeof($IDALL); $i++) {

            $varname1 = "";
            $varname1 .= "INSERT INTO photo_master ";
            $varname1 .= "            (id, owner, date_create, user_name, ";
            $varname1 .= "             photo_code) ";
            $varname1 .= "SELECT ".$i.",'".$owner."', now(),DATA_TEMP.user_name, ";
            $varname1 .= "       DATA_TEMP.photo_code ";
            $varname1 .= "FROM   (SELECT '" . $username . "'             AS USER_NAME, ";
            $varname1 .= "               '" . $IDALL[$i] . "' AS PHOTO_CODE ";
            $varname1 .= "        FROM   temp) AS DATA_TEMP ";
            $varname1 .= "WHERE  (( ((SELECT 1 ";
            $varname1 .= "            FROM   photo_master ";
            $varname1 .= "            WHERE  photo_master.photo_code = '" . $IDALL[$i] . "')) IS NULL ));";

            $conn->query($varname1);
        }
        
        $varname1 = " UPDATE USER_MASTER SET USER_MASTER.STATUS_GET_PHOTO = 'A' WHERE USER_MASTER.USER_NAME = '".$username."' ;";
        $conn->query($varname1);
        
        $conn->close();
    }

    public static function insertUserCode($owner, $username, $k) {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
        
        $conn->query(" DELETE FROM user_master WHERE owner = '".$owner."'; ");

        for ($i = 0; $i < $k; $i++) {

            $varname1 = "";
            $varname1 .= "INSERT INTO user_master ";
            $varname1 .= "            (id , date_create, owner,user_name) ";
            $varname1 .= "SELECT ".$i.", now(), '".$owner."', DATA_TEMP.user_name ";
            $varname1 .= "FROM   (SELECT '" . $username[$i] . "'             AS USER_NAME ";
            $varname1 .= "        FROM   temp) AS DATA_TEMP ";
            $varname1 .= "WHERE  (( ((SELECT 1 ";
            $varname1 .= "            FROM   user_master ";
            $varname1 .= "            WHERE  user_master.user_name = '" . $username[$i] . "')) IS NULL ));";
            //ECHO $varname1;
            $conn->query($varname1);
        }

        $conn->close();
    }

    public static function selecttUserCode() {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);

        $varname1 = "";
        $varname1 .= "SELECT user_master.user_name ";
        $varname1 .= "FROM   user_master ";
        $varname1 .= "WHERE  user_master.status_get_photo IS NULL;";

        $result = $conn->query($varname1);
        
        $userAll = [];
        for ($k = 0; $k < sizeof($result->_array); $k++) {

            $userAll[$k] = $result->_array[$k][0];
        }
        
        $conn->close();
        return $userAll;
    }
    

    public static function selecttPhotoCode($id) {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
        
        $varname1 = "";
        $varname1 .= "SELECT photo_master.photo_code ";
        $varname1 .= "FROM   photo_master ";
        $varname1 .= "WHERE  photo_master.status_like IS NULL ";
        $varname1 .= "       AND photo_master.id = ".$id." ";
        $varname1 .= "ORDER  BY photo_master.date_create;" ;
        
        $result = $conn->query($varname1);
        
        $PhotoCode = [];
        for ($k = 0; $k < sizeof($result->_array); $k++) {
            
            $PhotoCode[$k] = $result->_array[$k][0];
        }
        
        $conn->close();
        return $PhotoCode;
    }
    
    public static function updatePhotoCode($PhotoCode) {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
                
        $varname1 = "";
        $varname1 .= "UPDATE photo_master ";
        $varname1 .= "SET    photo_master.status_like = 'A', ";
        $varname1 .= "       photo_master.date_like = Now() ";
        $varname1 .= "WHERE  photo_master.photo_code = '".$PhotoCode."';" ;

        $conn->query($varname1);
        
        $conn->close();
    }
    
    public static function getNumUpdate() {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
                
        $varname1 = "";
        $varname1 .= "SELECT Count(*) AS Num ";
        $varname1 .= "FROM   user_master ";
        $varname1 .= "GROUP  BY user_master.status_get_photo ";
        $varname1 .= "HAVING user_master.status_get_photo = 'A';" ;
        
        $result = $conn->query($varname1);

        $NumUpdate = "0";
        for ($k = 0; $k < sizeof($result->_array); $k++) {
            
            $NumUpdate = $result->_array[$k][0];
        }
        
        $conn->close();
        return $NumUpdate;
    }
    
    public static function getNumLike() {

        require_once (__DIR__ . '/adodb/adodb.inc.php');
        $dsn = "DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . $_SERVER['DOCUMENT_ROOT'] . "/instagram-php/database/Database.mdb";

        $conn = & ADONewConnection('access');
        $conn->Connect($dsn);
                
        $varname1 = "";
        $varname1 .= "SELECT Count(*) AS Num ";
        $varname1 .= "FROM   photo_master ";
        $varname1 .= "GROUP  BY photo_master.status_like ";
        $varname1 .= "HAVING photo_master.status_like = 'A';" ;
        
        $result = $conn->query($varname1);

        $NumUpdate = "0";
        for ($k = 0; $k < sizeof($result->_array); $k++) {
            
            $NumUpdate = $result->_array[$k][0];
        }
        
        $conn->close();
        return $NumUpdate;
    }
}

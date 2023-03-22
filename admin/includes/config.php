<?php 
// Configuration de la connexion
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','library');

try
{
    // Connexion � la base
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
	// Echec de la connexion
    exit("Error: " . $e->getMessage());
}

error_reporting(E_ALL);

function myExceptionHandler ($e)
{
    error_log($e);
    http_response_code(500);
    if (filter_var(ini_get('display_errors'),FILTER_VALIDATE_BOOLEAN)) {
        echo $e;
    } else {
        echo "<h1>500 Internal Server Error</h1>
              An internal server error has been occurred.<br>
              Please try again later.";
    }
    exit;
}

set_exception_handler('myExceptionHandler');

set_error_handler(function ($level, $message, $file = '', $line = 0)
{
    throw new ErrorException($message, 0, $level, $file, $line);
});

register_shutdown_function(function ()
{
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
        myExceptionHandler($e);
    }
});

ini_set('display_errors', 1);

?>
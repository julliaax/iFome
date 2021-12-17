<?php

/* Credenciais do banco de dados. Supondo que você esteja executando o MySQL

servidor com configuração padrão (usuário 'root' sem senha) */

define('DB_SERVER', 'localhost');

define('DB_USERNAME', 'root');

define('DB_PASSWORD', '');

define('DB_NAME', 'ifome');

 

/* Tentativa de conexão com o banco de dados MySQL */

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

 

// Verifique a conexão

if($mysqli === false){

    die("ERROR: Could not connect. " . $mysqli->connect_error);

}

?>
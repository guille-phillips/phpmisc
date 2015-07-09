<?php

$db = new mysqli('server', 'user', 'password', 'database');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql = <<<SQL
    SELECT username,password,email,salt,group_id FROM exp_members WHERE email = 'guille.phillips@gmail.com'
SQL;


$sql = <<<SQL
    UPDATE exp_members SET group_id=1 WHERE username = 'guille.phillips@gmail.com'
SQL;


if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($row = $result->fetch_assoc()){
    var_dump($row);
    echo '<br><br>';
}

die("here"); // GMP


<?php
namespace sailboats;
use \PDO;

$db_config  = parse_ini_file(__DIR__ . '/config/db.conf');
$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

class whoami {
        function save($board=null, $thread=0) {
        global $servername, $dbname, $username, $password, $port;

        $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
        $sql = "INSERT INTO users (sha_id, user_agent, board, thread) VALUES (?,?,?,?)";
        $s = $conn->prepare($sql);
        $s->bindParam(1, sha1($_SERVER['REMOTE_ADDR']), PDO::PARAM_STR); // encrypted, not captured in app memory; used for api.cyberland2.club/api/ analytics
        $s->bindParam(2, $_SERVER['HTTP_USER_AGENT'],   PDO::PARAM_STR);
        $s->bindParam(3, $board,                        PDO::PARAM_STR);
        $s->bindParam(4, $thread,                       PDO::PARAM_INT);
        $s->execute();
        }
}
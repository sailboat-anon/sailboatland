<?php
require_once("RateLimit.php");

$servername = "localhost";
$username   = "cyberland";
// PASSWORD REDACTED
$dbname     = "cyberland";

function post($board)
{
    global $servername;
    global $dbname;
    global $username;
    global $password;

    $rl = new RateLimit();
    $st = $rl->getSleepTime($_SERVER["REMOTE_ADDR"]);
    echo $st;
    if ($st > 0) {
        echo "Please go away.";
    } elseif (!isset($_POST["content"])) {
        echo "Fuck off.";   
    } else {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $sql = "INSERT INTO ".$board. "(content, replyTo) VALUES (?,?);";
        $s = $conn->prepare($sql);
        $s->bindParam(2, isset($_POST["replyTo"]) ? $_POST["replyTo"] : 0, PDO::PARAM_INT);
        $s->bindParam(1, $_POST["content"], PDO::PARAM_STR);
        $s->execute();
        $r = $s->fetch();
        echo $r;
    }   
} 

function get($board)
{
    global $servername;
    global $dbname;
    global $username;
    global $password;
    
    if (isset($_GET["num"])) 
        $num = $_GET["num"];
    else 
        $num = 50
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);        
    if (isset($_GET["thread"])) {
        $sql = "SELECT * FROM ".$board." WHERE replyTo=? OR id=? ORDER BY id DESC LIMIT ?;";        
        $s = $conn->prepare($sql);
        $s->bindParam(1, $_GET["thread"], PDO::PARAM_INT);
        $s->bindParam(2, $_GET["thread"], PDO::PARAM_INT);
        $s->bindParam(3, $num, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM ".$board." ORDER BY id DESC LIMIT ?;";
        $s = $conn->prepare($sql);
        $s->bindParam(1, $num, PDO::PARAM_INT);        
    }         
    $s->execute();
    $r = $s->fetchAll();
    $a = array();
    foreach ($r as $result) {
        $result_aa = [
            "id" => $result["id"],
            "content" => $result["content"],
            "replyTo" => $result["replyTo"],
        ];
        array_push($a, $result_aa);
    }
    echo json_encode($a);
}

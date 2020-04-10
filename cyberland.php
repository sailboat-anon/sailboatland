<?php
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

    if (isset($_POST["content"])) {
        if (strlen($_POST["content"]) < 5000) {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            if (isset($_POST["replyTo"])) {
                $sql = "INSERT INTO $board (content, replyTo) VALUES (?,?);";
                $s = $conn->prepare($sql);
                $s->bindParam(2, $_POST["replyTo"], PDO::PARAM_INT);
            }    
            else {
                $sql = "INSERT INTO offtopic (content) VALUES (?);";
                $s = $conn->prepare($sql);
            }    
            $s->bindParam(1, $_POST["content"], PDO::PARAM_STR);
            $s->execute();
            $r = $s->fetch();
            echo $r;
            $s->close();
            $conn->close();        
        } else {
            echo "Fuck off.";
        }    
    } 
}

function get($board)
{
    global $servername;
    global $dbname;
    global $username;
    global $password;
    
    if (isset($_GET["num"])) {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);        
        if (isset($_GET["thread"])) {
            $sql = "SELECT * FROM $board WHERE replyTo=? OR id=? ORDER BY id DESC LIMIT ?;";        
            $s = $conn->prepare($sql);
            $s->bindParam(1, $_GET["thread"], PDO::PARAM_INT);
            $s->bindParam(2, $_GET["thread"], PDO::PARAM_INT);
            $s->bindParam(3, $_GET["num"], PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM offtopic ORDER BY id DESC LIMIT ?;";
            $s = $conn->prepare($sql);
            $s->bindParam(1, $_GET["num"], PDO::PARAM_INT);        
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
        $s->close();
        $conn->close();
    }
}

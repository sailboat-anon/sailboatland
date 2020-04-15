<?php
require __DIR__ . '/vendor/autoload.php';
require_once("RateLimit.php");
$db_config = parse_ini_file("config/db.conf");

$servername = $db_config["servername"];
$dbname     = $db_config["dbname"];
$username   = $db_config["username"];
$password   = $db_config["password"];
$port       = $db_config["port"];

function post(string $board): void
{
    global $servername;
    global $dbname;
    global $username;
    global $password;
    global $port;

    $torNodes  = file("../tornodes", FILE_IGNORE_NEW_LINES);
    if (in_array($_SERVER["REMOTE_ADDR"], $torNodes)) {
        header("HTTP/1.1 403 Forbidden", TRUE, 403);
        exit;
    }
    $rl = new RateLimit();
    $st = $rl->getSleepTime($_SERVER["REMOTE_ADDR"]);

    if ($st > 0) {
        header("HTTP/1.1 429 Too Many Requests", TRUE, 429);
        exit;
    } elseif (!isset($_POST["content"])) {
        header("HTTP/1.1 204 No Content", TRUE, 204);
        exit;
    } else {
        $replyTo = intval($_POST["replyTo"] ?? 0);
        $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
        $sql = "INSERT INTO {$board} (content, replyTo, bumpCount, time) VALUES (?,?,?,?)";
        $timeztamp = date("Y-m-d H:i:s");
        $bumpCount = 0;
        $s = $conn->prepare($sql);
        $s->bindParam(4, $timeztamp,        PDO::PARAM_STR);
        $s->bindParam(3, $bumpCount,        PDO::PARAM_INT);
        $s->bindParam(2, $replyTo,          PDO::PARAM_INT);
        $s->bindParam(1, $_POST["content"], PDO::PARAM_STR);
        $s->execute();
        echo $s->fetch();

        // If the reply wasn't to a board itself, bump the associated reply
        if ($replyTo != 0) {
            $s = $conn->prepare("UPDATE {$board} SET bumpCount = bumpCount + 1 WHERE id = ?");
            $s->bindParam(1, $replyTo, PDO::PARAM_INT);
            $s->execute();
            echo $s->fetch();
        }
    }
}

function get(string $board): void
{
    global $servername;
    global $dbname;
    global $username;
    global $password;
    global $port;

    $num = intval($_GET['num'] ?? 50);

    $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
    if (isset($_GET["thread"])) {
        $sql = "SELECT * FROM ".$board." WHERE replyTo=? OR id=? ORDER BY bumpCount DESC LIMIT ?";
        $s = $conn->prepare($sql);
        $s->bindParam(1, $_GET["thread"], PDO::PARAM_INT);
        $s->bindParam(2, $_GET["thread"], PDO::PARAM_INT);
        $s->bindParam(3, $num,            PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM {$board} ORDER BY bumpCount DESC LIMIT ?";
        $s = $conn->prepare($sql);
        $s->bindParam(1, $num, PDO::PARAM_INT);
    }
    $s->execute();
    $r = $s->fetchAll();
    $a = [];
    // Why is this here again?
    foreach ($r as $result) {
        $a[] = [
            "id"        => $result["id"],
            "content"   => $result["content"],
            "replyTo"   => $result["replyTo"],
            "bumpCount" => $result["bumpCount"],
            "time"      => $result["time"],
        ];
    }
    echo json_encode($a);
}

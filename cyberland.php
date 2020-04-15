<?php
require __DIR__ . '/vendor/autoload.php';
require_once("RateLimit.php");
$db_config = parse_ini_file("config/db.conf")

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

    $blacklist  = file("../config/blacklist", FILE_IGNORE_NEW_LINES);
    if (in_array($_SERVER["REMOTE_ADDR"], $blacklist)) {
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
        $sql = "INSERT INTO {$board} (content, replyTo) VALUES (?,?)";
        $bumpCount = 0;
        $s = $conn->prepare($sql);
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

    $sortOrder_hash = array("bumpCount", "time", "id");
    $sortHierarchy_hash = array("ASC", "DESC");

    $num = intval($_GET["num"] ?? 50);
    $thread = intval($_GET["thread"] ?? 0);

    if (isset($_GET["sortOrder"]) && in_array($_GET["sortOrder"], $sortOrder_hash)) {
        $sortOrder = $_GET["sortOrder"];
    }
    else {
        $sortOrder = "bumpCount";
    }
    if (isset($_GET["sortHierarchy"]) && in_array(strtoupper($_GET['sortHierarchy']), $sortHierarchy_hash)) {
        $sortHierarchy = $_GET['sortHierarchy'];
    }
    else {
        $sortHierarchy = "DESC";
    }

    $conn = new PDO("mysql:host={$servername};port={$port};dbname={$dbname}", $username, $password);
    if (isset($_GET["thread"])) {
        // ugly but bind()ing the variables to this thread screws up the sorting for some reason   
        $sql = "SELECT * FROM ".$board." WHERE replyTo=".$thread." OR id=".$thread." ORDER BY ".$sortOrder." ".$sortHierarchy." LIMIT ".$num;
    } else {
        $sql = "SELECT * FROM ".$board." ORDER BY ".$sortOrder." ".$sortHierarchy." LIMIT ".$num;
    }
    $s = $conn->prepare($sql);
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
    header('Content-Type: application/json');
    echo json_encode($a);
}

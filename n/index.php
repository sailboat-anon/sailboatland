<?php
include("../cyberland.php");
if (isset($_POST["content"])) {
    post("news");
} else if (isset($_GET["num"])) {
    get("news");
}

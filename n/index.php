<?php
include("../cyberland.php");
if (isset($_POST["content"])) {
    post("news");
} else {
    get("news");
}
?>
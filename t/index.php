<?php
include("../cyberland.php");
if (isset($_POST["content"])) {
    post("tech");
} else {
    get("tech");
}
?>
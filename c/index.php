<?php
include("../cyberland.php");
if (isset($_POST["content"])) {
    post("clients");
} else {
    get("clients");
}
?>

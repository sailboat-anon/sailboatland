<?php
include("../cyberland.php");
if (isset($_POST["content"])) {
    post("offtopic");
} else {
    get("offtopic");
}
?>
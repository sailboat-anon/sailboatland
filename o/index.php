require("../cyberland.php");
if (isset($_POST["content"])) {
    post("offtopic");
} else if (isset($_GET["num"])) {
    get("offtopic");
}

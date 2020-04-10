require("../cyberland.php");
if (isset($_POST["content"])) {
    post("tech");
} else if (isset($_GET["num"])) {
    get("tech");
}

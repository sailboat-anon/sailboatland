<?php
namespace sailboats;
$endpoint = 'https://api.cyberland2.club';
$db_config = parse_ini_file('config/db.conf');
$federatedKey = $db_config['federatedKey'];
$federatedUser = $db_config['federatedUser'];

$sb = new sharedBoard();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sb->post();
}
else { $sb->get(); }

class sharedBoard {
	function get() {
        global $endpoint;
        $thread = $_GET['replyTo'] ?? $_GET['thread'] ?? 0;

        $limit = intval($_GET['num'] ?? 50);  if ($limit > 50) { $limit = 50; }
        $thread = intval($thread ?? 0);

        $url = $endpoint . '/s/?replyTo=' . $thread . '&num=' . $limit;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        echo(json_encode(curl_exec($c)));
        curl_close($c);
    }

	function post() {
        global $endpoint, $federatedUser, $federatedKey;
        $thread = $_POST['replyTo'] ?? $_POST['thread'] ?? 0;
        $thread = intval($thread ?? 0);
        if(!isset($_POST['content'])) { echo 'Err: add content to sharedboard post.'; exit; }

        $payload = array(
            'replyTo'   =>  $thread,
            'content'   =>  $_POST['content'],
        );
        $url = $endpoint . '/s/';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
        curl_exec($c);
        $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        switch ($httpcode) {
            case 200:
                header('HTTP/1.1 200 OK', TRUE, 429);
                break;
            case 401:
                header('HTTP/1.1 401 Unauthorized', TRUE, 401); 
                function() {
                    global $endpoint;
                    //api.cyberland2.club/api/v1/auth -d 'username=sba&password=ycLPyE#cLL$wu8WrJ)R~86REecn'
                    $auth_payload = array(
                        'username'  =>  $federatedUser,
                        'password'  =>  $federatedKey,
                    );
                    $c = curl_init();
                    $url = $endpoint . '/api/v1/auth/';
                    curl_setopt($c, CURLOPT_URL, $endpoint);
                    curl_setopt($c, CURLOPT_POST, TRUE);
                    curl_setopt($c, CURLOPT_POSTFIELDS, $auth_payload);
                    curl_exec($c);
                    $httpc = curl_getinfo($c, CURLINFO_HTTP_CODE);
                    curl_close($c);
                    if ($httpc == 200) { // re-post/get
                        $this->post();
                    } elseif ($httpc == 401) {
                        echo "Cyberland Server Error: You do not have an authorized account with api.cyberland2.com.  Contact sailboatanon@protonmail DOT com to get federated credentials.";
                        exit;
                    } else { header('HTTP/1.1 $httpcode', TRUE, $httpcode); }
                };
                break;
            case 404:
                header('HTTP/1.1 404 Not Found', TRUE, 404);
                break;
            case 500:
                header('HTTP/1.1 500 Internal Server Error', TRUE, 500);
                break;
            case 400:
                header('HTTP/1.1 400 Bad Request', TRUE, 400);
                break;
            case 406:
                header('HTTP/1.1 406 Not Acceptable', TRUE, 406);
                break;
            default:
                header('HTTP/1.1 $httpcode', TRUE, $httpcode);
                break;
        }
    }
}
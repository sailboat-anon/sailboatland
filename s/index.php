<?php
namespace sailboats;
$endpoint = 'https://api.cyberland2.club';
$db_config = parse_ini_file('../config/db.conf');
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
        if (isset($_GET['replyTo'])) { $thread = $_GET['replyTo']; }
        else { $thread = $_GET['thread']; }

        $limit = intval($_GET['num'] ?? 1000);  if ($limit > 1000) { $limit = 1000; }

        $url = $endpoint . '/s/?replyTo=' . $thread . '&num=' . $limit;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $body = curl_exec($c);
        echo($body);
        $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);
    }

	function post() {
        global $endpoint, $federatedUser, $federatedKey;
        if (!isset($_POST['content']) || empty($_POST['content'])) { header('HTTP/1.1 400 Bad Request', TRUE, 400); exit; }
        if (isset($_GET['replyTo'])) { $thread = $_GET['replyTo']; }
        else { $thread = $_GET['thread']; }
        if (!is_numeric($thread)) { $thread = 0; }
        
        $auth_token = null;
        
        else { // get authoriation token
            $auth_payload = array(
                'username'  =>  $federatedUser,
                'password'  =>  $federatedKey,
            );
            $cauth = curl_init();
            $auth_url = $endpoint . '/api/v1/auth/';
            curl_setopt($cauth, CURLOPT_URL, $auth_url);
            curl_setopt($cauth, CURLOPT_POST, TRUE);                
            curl_setopt($cauth, CURLOPT_POSTFIELDS, $auth_payload);
            curl_setopt($cauth, CURLOPT_RETURNTRANSFER, TRUE);
            $r = json_decode(curl_exec($cauth));

            $httpc = curl_getinfo($cauth, CURLINFO_HTTP_CODE);
            if ($httpc == 200) { 
                $auth_token = $r->jwt;
            } elseif ($httpc == 401) {
                echo "Cyberland Server Error: This instance (". $_SERVER['HTTP_HOST'] .") does not have an authorized account with api.cyberland2.com.  Open an issue at https://github.com/sailboat-anon/api/issues to request your cyberland server to be federated!.\n";
                exit;
            } else {
                echo "Cyberland Server Error:  Uncaught HTTP Error $httpc \n";
                exit;
            }
            curl_close($cauth);
        }

        $payload = array(
            'replyTo'   =>  $thread,
            'content'   =>  $_POST['content'],
        );
        $url = $endpoint . '/s/';
        $c = curl_init();

        $hdr = array('Authorization: Bearer '.$auth_token);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, TRUE);   
        curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($c, CURLOPT_HTTPHEADER, $hdr);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $resp = json_decode(curl_exec($c));
        $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        header_remove(); 
        switch ($httpcode) {
            case 200:
                header('HTTP/1.1 200 OK', TRUE, 200);
                break;
            case 401:
                header('HTTP/1.1 401 Unauthorized', TRUE, 401);
                break;
            case 429:
                header('HTTP/1.1 429 Too Many Requests', TRUE, 429);
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
                header('HTTP/1.1 418 Ya Dun Goofed', TRUE, 418);
                break;
            }
        }
    }
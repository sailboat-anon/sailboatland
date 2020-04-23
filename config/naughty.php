<?php
namespace sailboats;
include __DIR__ . '/../vendor/autoload.php';
use Snipe\BanBuilder\CensorWords;

class sanitizeText {
	function profanity($text) {
		$censor = new CensorWords;
		$censor_obj = $censor->censorString($text);
		return($censor_obj['clean']);
	}
}

/*
(
    [orig] => You're a badword. You big c..t!. f..k !
    [clean] => You're a badword. You big ****!. **** !
    [matched] => Array
        (
            [0] => c..t
            [1] => f..k
        )

)
*/
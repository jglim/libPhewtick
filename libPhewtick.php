<?php

function getUrl($url)
{
	// http GET using iOS headers
	$ch = curl_init();
	$header = array('Phewtick/3.1.0 (iPhone; iOS 5.1.1; Scale/2.00)');
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
	$result = curl_exec( $ch );
	curl_close($ch);
	return $result;
}

function postUrl($url, $fields)
{
	// http POST using iOS headers
	$header = array('Phewtick/3.1.0 (iPhone; iOS 5.1.1; Scale/2.00)');
	$notify = curl_init();	
	curl_setopt($notify, CURLOPT_URL, $url);
	curl_setopt($notify, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($notify, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($notify, CURLOPT_HTTPHEADER, $header);
	$result = curl_exec($notify);
	curl_close($notify);
	return $result;
}

function locationEncode($location)
{
	// replaces period with the urlencoded data
	return str_replace('.', '%2E', $location);
}

function getLocationString($lat, $lng)
{
	// creates the 'urlencoded' location string
	return 'lng=' . locationEncode($lng) . '&lat=' . locationEncode($lat);
}


function ptIsValidToken($token)
{
	// checks if given token is valid
	$result = getUrl('http://v4api.phewtick.com/meets/qr?token=' . $token);
	if ($result === 'Invalid token')
	{
		return false;
	}
	else
	{
		return true;
	}
}

function ptGetQrCode($token)
{
	// gets the QR code of provided token
	$result = getUrl('http://v4api.phewtick.com/meets/qr?token=' . $token);
	$jsonResult = json_decode($result);
	return $jsonResult->qr_key;	
}

function ptSetMeetingBetweenTwoTokens($token1, $token2, $lat, $lng)
{
	// uses token2 to scan the QR code of token1
	$qrCodeOfToken1 = ptGetQrCode($token1);
	return ptSetMeeting($token2, $qrCodeOfToken1, $lat, $lng);
}

function ptSetMeeting($token, $qrcode, $lat, $lng)
{
	// performs the actual 'scanning'
	$postFields = getLocationString($lat, $lng) . '&qr_key=' . $qrcode . '&token=' . $token;
	$result = postUrl('http://v4api.phewtick.com/meets/meet', $postFields);
	return $result;
}

function ptLazySetMeeting($token, $qrcode)
{
	// sets up a meeting somewhere near marina bay mrt.
	// this is quite obvious and will probably get banned - location too precise and not noisy enough
	$postFields = 'lng=103%2E854760&lat=1%2E276153&qr_key=' . $qrcode . '&token=' . $token;
	$result = postUrl('http://v4api.phewtick.com/meets/meet', $postFields);
	return $result;
}
function ptLazySetLocation($token)
{
	// sets location near marina bay mrt
	// does not appear to be required!

	// you should probably not use this anyway
	$postFields = 'tz_offset=480&lng=103%2E854760&lat=1%2E276153&tz_id=Asia%2FSingapore&token=' . $token;
	$result = postUrl('http://v4api.phewtick.com/users/position', $postFields);
	// doesn't return anything
}


?>


<?php 

	// get tokens via any mitm methods e.g. Wireshark/Fiddler2/tcpdump
	$firstToken = "000000000000000000000000000000000";
	$secondToken = "111111111111111111111111111111111";

	$meetingLat = '1.276153';
	$meetingLng = '103.854760';

	echo ptSetMeetingBetweenTwoTokens($firstToken, $secondToken, $meetingLat. $meetingLng);

?>

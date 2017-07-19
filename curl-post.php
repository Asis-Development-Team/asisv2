 <?php

/*
function post_to_url($url, $data) {

    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= $key . '=' . $value . '&';
    }
    rtrim($fields, '&');

    $post = curl_init();

	$headers = array(
	     'Authorization: '.$apiKey
	);

    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($post, CURLOPT_HEADER, 1);
	curl_setopt($post, CURLINFO_HEADER_OUT, true);
    curl_setopt($post, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($post);

    curl_close($post);
    return $result;
}

$data = array(
    "name" => "new Name",
    "website" => "http://bavotasan.com",
    "twitterID" => "bavotasan"
);

$surl = 'http://v2.astonprinter.com/curl.php';

echo post_to_url($surl, $data);
*/


/*
function curl_header($data)
{
	$fields = '';
	foreach ($data as $key => $value) {
	    $fields .= $key . '=' . $value . '&';
	}
	rtrim($fields, '&');

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "http://v2.astonprinter.com/curl.php",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",


	  CURLOPT_HTTPHEADER => array(
	    "key: your-api-key"
	  ),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}
}

$data = array(
    "name" => "new Name",
    "website" => "http://bavotasan.com",
    "twitterID" => "bavotasan"
);


curl_header($data);
*/

$surl = 'http://v2.astonprinter.com/curl.php';

$name = 'Book name';

$url 		= 'http://v2.astonprinter.com/curl.php';
$api_key 	= '32Xhsdf7asd5'; // should match with Server key

$headers =	array(
				'key: '. $api_key
			);

$fields 	=	array(
					'name' => 'killy',
					'email' => 'dreamcorner@gmail.com'
				);

// Send request to Server
$ch = curl_init($url);
// To save response in a variable from server, set headers;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

// Get response
$response = curl_exec($ch);
// Decode
//$result = json_decode($response);

print $response;

?>
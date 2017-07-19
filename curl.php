<?php
//file_put_contents('abc.text', "here is my data " . implode('->', $_POST));
$headers = apache_request_headers();

print '<pre>';
print_r($_POST);
print '</pre>';

print '<pre>';
print_r(getallheaders());
print '</pre>';

//print_r($headers);


?>
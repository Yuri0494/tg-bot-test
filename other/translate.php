<?php

$IAM_TOKEN = 't1.9euelZqbx4mMip6JmY3Mk8uPmY6Qi-3rnpWampmVk4zJxoyPlY-Ll8ibl8fl9PdbEhhJ-e9qXh7-3fT3G0EVSfnval4e_s3n9euelZrImomdypCRj5qRlZ7OkMfPle_8xeuelZrImomdypCRj5qRlZ7OkMfPlQ.Lv8kZleHkk5oGeSeD4znbfc511gV1yqLYywduQLmStCLpFrP_PdXpUJiXkpOoQkngDIbXQK-6BVv5QXj6j6jAw';
$folder_id = 'b1ggjd0ie45tt2jhb3bq';
$target_language = 'ru';
$texts = ["Hello", "World"];

$url = 'https://translate.api.cloud.yandex.net/translate/v2/translate';

$headers = [
    'Content-Type: application/json',
    "Authorization: Bearer $IAM_TOKEN"
];

$post_data = [
    "targetLanguageCode" => $target_language,
    "texts" => $texts,
    "folderId" => $folder_id,
];

$data_json = json_encode($post_data);

$curl = curl_init();
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);

$result = curl_exec($curl);

curl_close($curl);

var_dump($result);

?>
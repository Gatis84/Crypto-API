<?php
namespace App;

//use Twig\Environment;
//use Twig\Loader\FilesystemLoader;


class Cmc implements Handler
{
    public string $time;

    public function getData():array
    {

$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$parameters = [
    'start' => '1',
    'limit' => '10',
    'convert' => 'USD'
];

$headers = [
    'Accepts: application/json',
    'X-CMC_PRO_API_KEY: 63933589-cc6f-4d74-9621-54d9da1ca5a7'
];

$qs = http_build_query($parameters);
$request = "{$url}?{$qs}";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $request,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_RETURNTRANSFER => 1
));

$response = curl_exec($curl);
echo "<pre>"; //noformate visu smuki

$myData = json_decode($response);

$filteredData=[];

for ($i=0;$i<$parameters['limit'];$i++) {
    $filteredData[] = ['id'=>$i,'name'=>$myData->data[$i]->name,'price'=>$myData->data[$i]->quote->USD->price];
}
    curl_close($curl);

//print_r($filteredData);
//        $loader = new FilesystemLoader('views');
//        $twig = new Environment($loader);
//        return $twig->render('index.Twig', ['coins' => $myData->data]);
        return $filteredData;
    }

}
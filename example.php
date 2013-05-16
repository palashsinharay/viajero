<?php
require_once('ean.php');
//below is a example of ean class usage
$EAN = new ean();

$arrayInfo["city"] = 'California';
$arrayInfo['countryCode'] = 'US';
$arrayInfo['checkIn'] = "05/15/2013";
$arrayInfo['checkOut'] = "05/16/2013";
$arrayInfo['rooms'] = "room1=1,3&room2=1,5";
$arrayInfo['numberOfResult'] = 10;
            
//$arrayInfo['propertyCategory'] = 1;
//$arrayInfo['amenities'] = 1;

$arrayInfo['maxStarRating']=5;
$arrayInfo['minStarRating']=3;

//$arrayInfo['minRate'] = 1000;
//$arrayInfo['maxRate'] = 10000;
            
$arrayInfo['sort'] = "QUALITY";

$arr=$EAN->LocationInfo($arrayInfo);
    echo "test";
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
    
$arrayInfo['cityId'] =  $arr['LocationInfoResponse']['LocationInfos']['LocationInfo'][0]['destinationId'];
//die();

$data['hotelListPage_'.$arrayInfo['city']] = $EAN->HotelLists($arrayInfo);

$arrayInfo['customerSessionId'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['customerSessionId'];
$arrayInfo['cacheKey'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['cacheKey'];
$arrayInfo['cacheLocation'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['cacheLocation'];

//$data['hotelListPage_'.$arrayInfo['city'].'2'] = $EAN->HotelListsMore($arrayInfo);
//foreach ($data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['HotelList']['HotelSummary'] as $key => $value) {
//    $arrayInfo['hotelIds'][] = $value['hotelId'];
//}



echo "<pre>";
print_r($data);
//print_r($arrayInfo['hotelIds']);
//foreach ($arrayInfo['hotelIds'] as $value) {
//    $arrayInfo['hotelId'] = $value; 
//    $data['hotelDetail_'.$value] = $EAN->HotelDetails($arrayInfo);
//    $data['hotelRoomAvailability_'.$value] = $EAN->HotelRoomAvailability($arrayInfo);
//    print_r($data['hotelRoomAvailability_'.$value]);
//    
//   
//}
?>
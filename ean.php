<?php
class ean {
	
        public  $cid;
	public  $apiKey;
	public  $local;
	public  $currency;
	
	public function __construct($_cid = '55505' ,$_apiKey = "38rwz7wgs2x2jt8qt2twq3cb" ,$_local = "en_US",$_currency = "USD"){
        $this->cid = $_cid;
	$this->apiKey = $_apiKey;
	$this->local = $_local;
	$this->currency = $_currency;
	
	}
        
        /*
         * function for API call using curl
         */
	function apiCall($url){
            
            $url = str_replace(" ", '%20', $url);   // url encode for space
//            $ch=curl_init($url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $r=curl_exec($ch);
//            curl_close($ch);
//            $response = json_decode($r,true);
            

            
            $header[] = "Accept: application/json";
            $header[] = "Accept-Encoding: gzip";
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
            curl_setopt($ch,CURLOPT_ENCODING , "gzip");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $response = json_decode(curl_exec($ch),true);
            
            $curlinfo = curl_getinfo($ch); 
            
            //echo "<pre>";
            //print_r($curlinfo);
            
            if(array_key_exists('EanWsError',$response)){
                echo "<pre>";
                print_r($response);
                echo "<pre>";
                die();
                
            } else{
                return $response;  
            }
            
			
        }
        
        /*
         * funtion to get the list of hotels
         */
			
	function HotelLists($arrayInfo){
            
            $city = $arrayInfo['city'];
            $countryCode = $arrayInfo['countryCode'];
            $checkIn = $arrayInfo['checkIn'];
            $checkOut = $arrayInfo['checkOut'];
            $rooms = $arrayInfo['rooms'];
            $numberOfResult = array_key_exists('numberOfResult', $arrayInfo) ? $arrayInfo['numberOfResult'] :10;
            
            /*filtering
             * please check the Filtering Methods section for compleate list http://developer.ean.com/docs/read/hotel_list
             */
            $propertyCategory = array_key_exists('propertyCategory', $arrayInfo) ? $arrayInfo['propertyCategory'] : '' ;
            $amenities = array_key_exists('amenities', $arrayInfo) ? $arrayInfo['amenities'] : '' ;
            $maxStarRating = array_key_exists('maxStarRating', $arrayInfo) ? $arrayInfo['maxStarRating'] : '' ;
            $minStarRating = array_key_exists('minStarRating', $arrayInfo) ? $arrayInfo['minStarRating'] : '' ;
            $minRate = array_key_exists('minRate', $arrayInfo) ? $arrayInfo['minRate'] : '' ;
            $maxRate = array_key_exists('maxRate', $arrayInfo) ? $arrayInfo['maxRate'] : '' ;
            
            /*
             * sorting
             * please check the Sorting Options section for compleate list http://developer.ean.com/docs/read/hotel_list
             * 
             */
            $sort = $arrayInfo['sort'] = array_key_exists('sort', $arrayInfo) ? $arrayInfo['sort'] : 'NO_SORT' ;
            
            $str= 'http://api.ean.com/ean-services/rs/hotel/v3/list?minorRev=21&cid='.$this->cid.
                    '&apiKey='.$this->apiKey.'&customerSessionId&customerUserAgent&customerIpAddress&locale='.$this->local.
                    '&currencyCode='.$this->currency.
                    '&destinationString='.$city.
                    '&countryCode='.$countryCode.
                    '&propertyCategory='.$propertyCategory.
                    '&amenities='.$amenities.
                    '&maxStarRating='.$maxStarRating.
                    '&minStarRating='.$minStarRating.
                    '&minRate='.$minRate.
                    '&maxRate'.$maxRate.
                    '&sort='.$sort.
                    '&supplierCacheTolerance=MED&arrivalDate='.$checkIn.
                    '&departureDate='.$checkOut.'&'.$rooms.'&numberOfResults='.$numberOfResult.
                    '&supplierCacheTolerance=MED_ENHANCED';
            
            return $this->apiCall($str);
	}
        
        /*
         * function to get hotelList more page
         */
        function HotelListsMore($arrayInfo){
            $customerUserAgent = trim($_SERVER['HTTP_USER_AGENT']);
            $customerIpAddress = trim($_SERVER['REMOTE_ADDR']);
            $customerSessionId = trim($arrayInfo['customerSessionId']);
            $cacheKey = trim($arrayInfo['cacheKey']);
            $cacheLocation = trim($arrayInfo['cacheLocation']);
            
            $str = "http://api.ean.com/ean-services/rs/hotel/v3/list?minorRev=21&cid=".$this->cid."&apiKey=".$this->apiKey."&customerUserAgent=".$customerUserAgent."customerIpAddress=".$customerIpAddress."&customerSessionId=".$customerSessionId."&locale=".$this->local."&currencyCode=".$this->currency."&cacheKey=".$cacheKey."&cacheLocation=".$cacheLocation;
            
            return $this->apiCall($str);
            
            
        }

	

}

//below is a example of ean class usage
$EAN = new ean();

$arrayInfo["city"] = 'mumbai';
$arrayInfo['countryCode'] = 'IN';
$arrayInfo['checkIn'] = "05/14/2013";
$arrayInfo['checkOut'] = "05/15/2013";
$arrayInfo['rooms'] = "room1=1,3&room2=1,5";
$arrayInfo['numberOfResult'] = 10;
            
//$arrayInfo['propertyCategory'] = 1;
//$arrayInfo['amenities'] = 1;

$arrayInfo['maxStarRating']=5;
$arrayInfo['minStarRating']=3;

//$arrayInfo['minRate'] = 1000;
//$arrayInfo['maxRate'] = 10000;
            
$arrayInfo['sort'] = "QUALITY";

$data['hotelListPage_'.$arrayInfo['city']] = $EAN->HotelLists($arrayInfo);

$arrayInfo['customerSessionId'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['customerSessionId'];
$arrayInfo['cacheKey'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['cacheKey'];
$arrayInfo['cacheLocation'] = $data['hotelListPage_'.$arrayInfo['city']]['HotelListResponse']['cacheLocation'];

$data['hotelListPage_'.$arrayInfo['city'].'2'] = $EAN->HotelListsMore($arrayInfo);

echo "<pre>";
print_r($data);


?>

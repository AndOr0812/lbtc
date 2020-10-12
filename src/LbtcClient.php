<?php
namespace Ndlovu28\Lbtc;

class LbtcClient{
	protected $apiauth_key;
	protected $apiauth_secret;

	function __construct($key, $secret){	
		$this->apiauth_key = $key;
		$this->apiauth_secret = $secret;
	}

	function send($endpoint, $query_type, array $rec = Array(), array $data = Array()){
		$key = $this->apiauth_key;
		$secret = $this->apiauth_secret;

		$mt = explode(' ', microtime());
    	$nonce = $mt[1].substr($mt[0], 2, 6);
		
		$url = "https://localbitcoins.com".$endpoint;
        //echo "URL: ".$url;

		$get = "";
    	if ($rec) {
        	$get=http_build_query($rec);
    	}
        if($data){
            $get = http_build_query($data);
        }
    	
    	$postdata = $nonce.$key.$endpoint.$get;
    	$sign = strtoupper(hash_hmac('sha256', $postdata, $secret));

    	$headers = array(
        	'Apiauth-Signature:'.$sign,
        	'Apiauth-Key:'.$key,
        	'Apiauth-Nonce:'.$nonce
    	);

    	$ch = null;
    	$ch = curl_init();

    	if($query_type == "get"){
        	$endpoint = $endpoint.'?'.$get;
        }

    	curl_setopt($ch, CURLOPT_URL,"https://localbitcoins.com".$endpoint);
    	
    	if($query_type == "post"){
    		$data_string = json_encode($data);
    		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $get);
    	}
    	
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    	$res = curl_exec($ch);
        
    	//if ($res === false) throw new Exception('Curl error: '.curl_error($ch));
    	$dec = json_decode($res, true);
    	//if (!$dec) throw new Exception('Invalid data: '.$res);
    	//curl_close($ch);
    	return $dec;
	}

    function send_no_auth($endpoint){
        $url = 'https://localbitcoins.com'.$endpoint;

        $res = file_get_contents($url);
        $res = json_decode($res, true);

        return $res;
    }
}
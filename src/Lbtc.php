<?php
namespace Ndlovu28\Lbtc;

use Ndlovu28\Lbtc\LbtcClient;
use Log;

use Ndlovu28\Lbtc\Model\Lbtc AS LbtcData;

class Lbtc{
	private $key;
	private $secret;

	private $client;

	function config($key, $secret){
		$this->key = $key;
		$this->secret = $secret;
		$this->client = new LbtcClient($key, $secret);
	}

	/**
	Check Wallet balance
	@return Array || false
	**/
	function checkBalance(){
		$endpoint = '/api/wallet-balance/';
		$query_type = 'get';

		$res = $this->client->send($endpoint, $query_type);

		if($res['data']){
			if($res['data']['total']['balance']){
				$balance = $res['data']['total']['balance'];
				$address = $res['data']['receiving_address'];

				$arr = array();
				$arr['balance'] = $balance;
				$arr['receiving_address'] = $address;

				return $arr;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}

	/**
	Get sellers for a specific payment method
	@param amount - amount in BTC to sell
	@param payment_method = Method to recieve fiat in
	
	@return ad_id - Advert ID of buyer
	**/
	function getBuyers($amount, $payment_method, $country, $country_code){
		$endpoint = '/sell-bitcoins-online/'.$country_code.'/'.$country.'/'.$payment_method.'/.json';
		$query_type = 'get';

		$res = $this->client->send_no_auth($endpoint, $query_type);
		if(isset($res['data'])){
			$data = $res['data'];
			if(isset($data['ad_list'])){
				$ad_list = $data['ad_list'];
				foreach($ad_list AS $ad){
					$min = $ad['data']['min_amount'];
					$max = $ad['data']['max_amount'];
					if($amount >= $min && $amount <= $max){
						return $ad['data']['ad_id'];
					}
				}
			}
		}

	}

	function getBuyerByID($ad_id){
		$endpoint = '/api/ad-get/'.$ad_id.'/';
		$res = $this->client->send($endpoint, 'get');
		if($res){
			$data = $res['data']['ad_list'][0]['data'];

			$ad_id = $data['ad_id'];
			$btc_price = $data['temp_price'];
			$btc_price_usd = $data['temp_price_usd'];

			$arr = array();
			$arr['ad_id'] = $ad_id;
			$arr['btc_price'] = $btc_price;
			$arr['btc_price_usd'] = $btc_price_usd;
			return $arr;
		}
	}

	function initTrade($ad_id, $amount, $message, $trx_data, $trx_id=null, $currency=null){
		Log::info("Starting trade");
		$endpoint = '/api/contact_create/'.$ad_id.'/';
		$rec = array();
		$data = array();
		$data = $trx_data;
		$data['amount'] = $amount;
		$data['message'] = $message;

		$res = $this->client->send($endpoint, 'post', $rec, $data);
		if($res){
			if(isset($res['data']['message'])){
				if($res['data']['message'] == "OK!"){
					$contact_id = $res['data']['contact_id'];

					$lbtc = LbtcData::create([
						'trx_id'=>$trx_id,
						'ad_id'=>$ad_id,
						'contact_id'=>$contact_id,
						'amount'=>$amount,
						'currency'=>$currency,
						'status'=>'pending'
					]);

					$arr = array();
					$arr['contact_id'] = $contact_id;
					$arr['record_id'] = $lbtc->id;

					return $arr;
				}
				Log::error($res);
			}
		}
		return false;
	}

	function sendMessage($contact_id, $message){
		$endpoint = '/api/contact_message_post/'.$contact_id.'/';
		$rec = array();
		$data = array();
		$data['msg'] = $message;

		$res = $this->client->send($endpoint, 'post', $rec, $data);
		if($res){
			if(isset($res['data']['message'])){
				if($res['data']['message'] == "Message sent successfully."){
					return true;
				}
			}
		}
		return false;
	}

	function getMessages($contact_id){
		$endpoint = '/api/contact_messages/'.$contact_id.'/';
		$res = $this->client->send($endpoint, 'get');
		
		$arr = array();
		if($res){
			$data = $res['data'];
			if(isset($data['message_list'])){
				$msgs = $data['message_list'];
				foreach($msgs AS $msg){
					$ms = array();
					$ms['msg'] = $msg['msg'];
					$ms['date'] = $msg['created_at'];
					$ms['sender'] = $msg['sender']['username'];

					$arr[] = $ms;
				}
			}
		}
		return $arr;
	}

	function checkTrade($contact_id){
		$endpoint = '/api/contact_info/'.$contact_id.'/';
		$res = $this->client->send($endpoint, 'get');

		$data = $res['data'];
		
		return $data;
	}

	function releseTrade($contact_id){
		$endpoint = '/api/contact_release/'.$contact_id.'/';
		$rec = array();
		$data = array();
		$data['contact_id'] = $contact_id;

		$res = $this->client->send($endpoint, 'post', $rec, $data);
		$data = $res['data'];
		if($data['message'] == "The escrow of contact has been released successfully."){
			return true;
		}
		return false;
	}
}
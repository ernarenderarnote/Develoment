<?php
namespace App\Classes;
use File;
use Form;
use Session;
use DB;
use Validator;
use Auth;
use Entrust;

	Class Helper{

		public static function validateIp(){

			$ip = \Request::getClientIp();

			$wl_ips = \App\Ip::all();
			$allowedIps = array();
			foreach($wl_ips as $wl_ip){
				if($wl_ip->end)
					$allowedIps[] = $wl_ip->start.'-'.$wl_ip->end;
				else
					$allowedIps[] = $wl_ip->start;
			}

			foreach ($allowedIps as $allowedIp) 
	        {
	            if (strpos($allowedIp, '*')) 
	            {
	                $range = [ 
	                    str_replace('*', '0', $allowedIp),
	                    str_replace('*', '255', $allowedIp)
	                ];
	                if(Helper::ipExistsInRange($range, $ip)) return true;
	            } 
	            else if(strpos($allowedIp, '-'))
	            {
	                $range = explode('-', str_replace(' ', '', $allowedIp));
	                if(Helper::ipExistsInRange($range, $ip)) return true;
	            }
	            else 
	            {
	                if (ip2long($allowedIp) === ip2long($ip)) return true;
	            }
	        }
	        return false;
        }
        
		public static function callCurl($url,$postData){
			$ch = curl_init($url);
	        curl_setopt_array($ch, array(
	            CURLOPT_URL => $url,
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_POST => true,
	            CURLOPT_POSTFIELDS => $postData
	        ));
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	        $data = curl_exec($ch);
	        if(curl_errno($ch))
	            return response()->json(['error' => '108']);

	        curl_close($ch);

	        return json_decode($data, true);
		}

		public static function translateList($lists){
			$translated_list = array();
			foreach($lists as $key => $list)
				$translated_list[$key] = trans('messages.'.$key);

			return $translated_list;
		}

		public static function createSlug($string){
		   if(Helper::checkUnicode($string))
		   		$slug = str_replace(' ', '-', $string);
		   else
		   		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
		   return $slug;
		}
		
		public static function checkUnicode($string)
		{
			if(strlen($string) != strlen(utf8_decode($string)))
			return true;
			else
			return false;
		}

	}
?>
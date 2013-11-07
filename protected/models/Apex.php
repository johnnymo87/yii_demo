<?php

class Apex {
	const URL = 'http://shielded-mesa-1340.herokuapp.com/';
	public $machines;

	function get($url) {
		return file_get_contents(self::URL . $url);
	}

	# http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php
	function post($url, $params) {
		$options = array(
		    'http' => array(
        		'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        		'method'  => 'POST',
        		'content' => http_build_query($params),
    		),
		);
		$context  = stream_context_create($options);
		return file_get_contents(self::URL . $url, false, $context);
	}

	function login($username, $password) {
		$params = array('username'=>$username,'password'=>$password);
		return self::post('login.json', $params);
	}

	public function __construct($username, $password) {
		$params = array('username'=>$username,'password'=>$password);
		return self::post('login.json', $params);
	}
	
	# http://stackoverflow.com/questions/4260086/php-how-to-use-array-filter-to-filter-array-keys
	public function find_machines() {
		self::get('customers.json');
		$nested_hash = json_decode(self::get('machines.json'), true);
		$allowed = array('deviceName', 'companyName', 'deviceId', 'siteId', 'companyId');
		foreach($nested_hash as $machine=>$data) {
			$this->machines[$machine] = array_intersect_key($data, array_flip($allowed));
		}  
	}

}

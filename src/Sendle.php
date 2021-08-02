<?php

namespace Cognito\Sendle;

use Exception;

/**
 * Interact with the Sendle API
 *
 * @package Cognito
 * @author Josh Marshall <josh@jmarshall.com.au>
 */
class Sendle {

	private $api_id  = null;
	private $api_key = null;
	private $host = null;

	const API_SCHEME       = 'https://';
	const API_HOST         = 'api.sendle.com';
	const API_SANDBOX_HOST = 'sandbox.sendle.com';

	/**
	 *
	 * @param string $api_id The Sendle API ID
	 * @param string $api_key The Sendle API Key
	 * @param bool $use_sandbox
	 */
	public function __construct($api_id, $api_key, $use_sandbox = false) {
		$this->api_id  = $api_id;
		$this->api_key = $api_key;
		$this->host = self::API_SCHEME;
		$this->host .= $use_sandbox ? self::API_SANDBOX_HOST : self::API_HOST;
	}

	/**
	 * Check if we have access to the API (tests ID and Key)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function checkAccess() {
		$data = json_decode($this->sendGetRequest('/api/ping'), true);
		if (isset($data['error_description'])) {
			throw new \Exception($data['error_description']);
		}
		return array_key_exists('ping', $data);
	}

	/**
	 * Get list of Australia Post standard parcel sizes
	 *
	 * @return ParcelSize[] the parcel sizes
	 * @throws Exception
	 */
	public function getParcelSizes() {
		$data = json_decode($this->sendGetRequest('/postage/parcel/domestic/size.json' . $this->account_number), true);
		$parcels = [];
		foreach ($data['sizes']['size'] as $item) {
			$parcels[] = new ParcelSize($item);
		}

		return $parcels;
	}

	/**
	 * Start point of a quote
	 * @return QuoteRequest
	 */
	public function startQuote() {
		return new QuoteRequest($this);
	}

	/**
	 * Sends an HTTP GET request to the API.
	 *
	 * @param string $action the API action component of the URI
	 * @param array $data optional key => value data to send in the url
	 * @return string raw body data
	 * @throws Exception on error
	 */
	public function sendGetRequest($action, $data = []) {
		$url = $this->host . $action;
		if ($data) {
			$url .= '?' . http_build_query($data);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $this->api_id . ':' . $this->api_key);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Accept: application/json',
		]);
		$rawBody = curl_exec($ch);

		if (!$rawBody) {
			throw new Exception('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}

		return $rawBody;
	}
}

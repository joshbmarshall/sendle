<?php

namespace Cognito\Sendle;

/**
 * A shipment, made up of one or more parcels
 *
 * @package Cognito\Sendle
 * @author Josh Marshall <josh@jmarshall.com.au>
 *
 * @property Sendle $_sendle
 * @property string $from_postcode
 * @property string $to_postcode
 * @property float $length
 * @property float $width
 * @property float $height
 * @property float $weight
 * @property float $cubic_weight
 * @property float $cubic_weight_multiplier
 */
class QuoteRequest {

	private $_sendle;
	public $from_postcode;
	public $from_suburb;
	public $to_postcode;
	public $to_suburb;
	public $length = 0;
	public $width  = 0;
	public $height = 0;
	public $weight;
	public $cubic_weight = 0;
	public $cubic_weight_multiplier = 250;

	public function __construct($api) {
		$this->_sendle = $api;
	}

	/**
	 * Add the From Postcode
	 * @param int $data The Australian postcode to deliver to
	 * @return $this
	 */
	public function setFromPostcode($data) {
		$this->from_postcode = $data;
		return $this;
	}

	/**
	 * Add the From Suburb
	 * @param int $data The Australian suburb to deliver to
	 * @return $this
	 */
	public function setFromSuburb($data) {
		$this->from_suburb = $data;
		return $this;
	}

	/**
	 * Add the To Postcode
	 * @param int $data The Australian postcode to deliver to
	 * @return $this
	 */
	public function setToPostcode($data) {
		$this->to_postcode = $data;
		return $this;
	}

	/**
	 * Add the To Suburb
	 * @param int $data The Australian suburb to deliver to
	 * @return $this
	 */
	public function setToSuburb($data) {
		$this->to_suburb = $data;
		return $this;
	}

	/**
	 * Set the physical size of the parcel in centimeters
	 * @param int $length
	 * @param int $width
	 * @param int $height
	 * @return $this
	 */
	public function setParcelDimensions($length, $width, $height) {
		$this->length = $length;
		$this->width  = $width;
		$this->height = $height;
		return $this;
	}

	/**
	 * Set the actual weight of the parcel in kilograms
	 * @param float $weight
	 * @return $this
	 */
	public function setParcelWeight($weight) {
		$this->weight = $weight;
		return $this;
	}

	/**
	 * Set the cubic weight of the parcel. This is used if a physical size isn't known but the approximate volume is.
	 * Uses the Australia Post domestic cubic weight to approximate parcel size
	 * @param float $weight
	 * @param float $multiplier Defaults to 250
	 * @return $this
	 */
	public function setParcelCubicWeight($weight, $multiplier = 250) {
		if (!$this->weight) {
			$this->weight = $weight;
		}
		if (!$multiplier) {
			$multiplier = 250;
		}
		$this->cubic_weight = $weight;
		$this->cubic_weight_multiplier = $multiplier;
		// Cubic weight = length (m) * width (m) * height (m) * multiplier
		return $this->setVolume($weight * 1000 / $multiplier);
	}

	/**
	 * Set the actual volume of the parcel in litres
	 * @param float $volume
	 * @return $this
	 */
	public function setVolume($volume) {
		// Each side in cm = (volume * 1000) ^1/3
		$cube = round(pow($volume * 1000, 1 / 3), 5);
		$this->length = $cube;
		$this->width = $cube;
		$this->height = $cube;
		return $this;
	}

	/**
	 * Get quote for this parcel
	 * @return Quote
	 */
	public function getQuote() {
		$data = json_decode($this->_sendle->sendGetRequest('/api/quote', [
			'pickup_postcode'   => $this->from_postcode,
			'pickup_suburb'     => $this->from_suburb,
			'delivery_postcode' => $this->to_postcode,
			'delivery_suburb'   => $this->to_suburb,
			'volume_value'      => round($this->length * $this->width * $this->height / 1000, 2),   // cubic centimeters to litres
			'volume_units'      => 'l',                                                             // litres
			'weight_value'      => $this->weight,
			'weight_units'      => 'kg',
		]), true);

		foreach ($data as $item) {
			return new Quote($item);
		}
	}
}

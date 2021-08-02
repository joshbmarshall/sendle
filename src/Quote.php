<?php

namespace Cognito\Sendle;

/**
 * Definition of a Sendle Quote
 *
 * @package Cognito\Sendle
 * @author Josh Marshall <josh@jmarshall.com.au>
 *
 * @property float $gross_amount
 * @property float $net_amount
 * @property float $tax_amount
 * @property string $currency
 * @property string $plan_name
 * @property Eta $eta
 * @property Route $route
 */
class Quote {

	public $gross_amount;
	public $net_amount;
	public $tax_amount;
	public $currency;
	public $plan_name;
	public $eta;
	public $route;
	public $raw_details = [];

	public function __construct($details) {
		foreach ($details as $key => $data) {
			if ($key == 'eta') {
				$this->$key = new Eta($data);
			} else if ($key == 'route') {
				$this->$key = new Route($data);
			} else if ($key == 'quote') {
				$this->gross_amount = $data['gross']['amount'];
				$this->net_amount   = $data['net']['amount'];
				$this->tax_amount   = $data['tax']['amount'];
				$this->currency     = $data['gross']['currency'];
			} else {
				$this->$key = $data;
			}
		}
		$this->raw_details = $details;
	}
}

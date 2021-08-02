<?php

namespace Cognito\Sendle;

/**
 * Definition of a Sendle Route
 *
 * @package Cognito\Sendle
 * @author Josh Marshall <josh@jmarshall.com.au>
 *
 * @property string $type
 * @property string $description
 */
class Route {

	public $type;
	public $description;
	public $raw_details = [];

	public function __construct($details) {
		foreach ($details as $key => $data) {
			$this->$key = $data;
		}
		$this->raw_details = $details;
	}
}

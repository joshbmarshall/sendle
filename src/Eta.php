<?php

namespace Cognito\Sendle;

/**
 * Definition of a Sendle ETA
 *
 * @package Cognito\Sendle
 * @author Josh Marshall <josh@jmarshall.com.au>
 *
 * @property string $for_pickup_date
 * @property int $days_from
 * @property int $days_to
 * @property string $deliver_from_date
 * @property string $deliver_to_date
 */
class Eta {

    public $for_pickup_date;
    public $days_from;
    public $days_to;
    public $deliver_from_date;
    public $deliver_to_date;
    public $raw_details = [];

    public function __construct($details) {
        foreach ($details as $key => $data) {
            if ($key == 'days_range') {
                $this->days_from = $data[0];
                $this->days_to = $data[1];
            } else if ($key == 'date_range') {
                $this->deliver_from_date = $data[0];
                $this->deliver_to_date = $data[1];
            } else {
                $this->$key = $data;
            }
        }
        $this->raw_details = $details;
    }
}

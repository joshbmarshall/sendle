# Sendle API

Interact with the Sendle API

## Installation

Installation is very easy with composer:

    composer require cognito/sendle

## Setup

Create an account at Sendle, login and go to Settings > Integrations.
Copy your API Key and Sendle ID from the page.

## Usage

```
<?php
	$sendle = new \Cognito\Sendle\Sendle('Your Sendle API ID', 'Your Sendle API Key');

	// Get a quote for delivery options using known weight and box dimensions
	$quotes = $sendle->startQuote()
		->setFromPostcode(4500)
		->setToPostcode(4127)
		->setParcelDimensions(10, 8, 22)
		->setParcelWeight(2.3)
		->getServices();

	// Get a quote for delivery options using known weight and approximate box dimensions from cubic weight
	$quotes = $sendle->startQuote()
		->setFromPostcode(4500)
		->setToPostcode(4127)
		->setParcelWeight(2.3)
		->setParcelCubicWeight(4)
		->getServices();

	// Get a quote for delivery options using cubic weight
	$quotes = $sendle->startQuote()
		->setFromPostcode(4500)
		->setToPostcode(4127)
		->setParcelCubicWeight(4)
		->getServices();

	// Get a total price for a delivery of a certain type
	$price = $auspost->startQuote()
		->setFromPostcode(4500)
		->setToPostcode(4127)
		->setParcelCubicWeight(4)
		->getTotalPrice('AUS_PARCEL_REGULAR');
```

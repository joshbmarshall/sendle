# Sendle API

Interact with the Sendle API. Currently it is for getting quotes only.

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
	$quote = $sendle->startQuote()
		->setFromPostcode(4500)
		->setFromSuburb('Strathpine')
		->setToPostcode(4127)
		->setToSuburb('Underwood')
		->setParcelDimensions(10, 8, 22)
		->setParcelWeight(2.3)
		->getQuote();

	// Get a quote for delivery options using known weight and approximate box dimensions from cubic weight
	$quote = $sendle->startQuote()
		->setFromPostcode(4500)
		->setFromSuburb('Strathpine')
		->setToPostcode(4127)
		->setToSuburb('Underwood')
		->setParcelWeight(2.3)
		->setParcelCubicWeight(4)
		->getQuote();

	// Get a quote for delivery options using known weight and volume in litres
	$quote = $sendle->startQuote()
		->setFromPostcode(4500)
		->setFromSuburb('Strathpine')
		->setToPostcode(4127)
		->setToSuburb('Underwood')
		->setParcelWeight(2.3)
		->setVolume(4)
		->getQuote();

	// Get a quote for delivery options using cubic weight
	$quote = $sendle->startQuote()
		->setFromPostcode(4500)
		->setFromSuburb('Strathpine')
		->setToPostcode(4127)
		->setToSuburb('Underwood')
		->setParcelCubicWeight(4)
		->getQuote();

```

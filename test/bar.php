<?php
require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

echo $generator->getBarcode('100000000000000', $generator::TYPE_PLANET);

require 'vendor/picqer/php-barcode-generator/tests/BarcodePngTest.php';
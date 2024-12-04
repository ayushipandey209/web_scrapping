<?php
$htmlContent = file_get_contents('dom.txt');
$dom = new DOMDocument();
libxml_use_internal_errors(true); 
$dom->loadHTML($htmlContent);
libxml_clear_errors();
$xpath = new DOMXPath($dom);
$couponContainers = $xpath->query("//div[contains(@class, 'post-wrapper')]");

$outputFile = 'coupons.txt';
file_put_contents($outputFile, ""); 

foreach ($couponContainers as $container) {
    $couponData = [];

    $backgroundDiv = $xpath->query(".//div[contains(@style, 'background-image')]", $container)->item(0);
    if ($backgroundDiv) {
        $style = $backgroundDiv->getAttribute('style');
        $start = strpos($style, 'url(') + 4;
        $end = strpos($style, ')', $start);
        $couponData['background_image_url'] = substr($style, $start, $end - $start);
    } else {
        $couponData['background_image_url'] = 'N/A';
    }

    // Fetch the title
    $titleNode = $xpath->query(".//div[contains(@class, 'content is-relative')]//h4[@class='post-title brand-white font-semibold no-margin-bottom']", $container);
    $couponData['title'] = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : 'N/A';

    // Fetch the description
    $descriptionNode = $xpath->query(".//div[contains(@class, 'content is-relative')]//p[@class='post-text brand-white font-medium no-margin-bottom']", $container);
    $couponData['description'] = $descriptionNode->length > 0 ? trim($descriptionNode->item(0)->textContent) : 'N/A';

    //fetch the coupon
    $codeNode = $xpath->query(".//div[contains(@class, 'coupon-wrapper')]//span[contains(@class, 'font-semibold text-uppercase gray-dark copy-text')]", $container);
 $couponData['coupon_code'] = $codeNode->length > 0 ? trim($codeNode->item(0)->textContent) : 'N/A';

    // Fetch the booking URL
    $urlNode = $xpath->query(".//div[contains(@class, 'button-wrapper text-right')]//a[@class='button font-semibold is-inline-flex text-uppercase align-items-center justify-content-end']", $container);
    $couponData['book_url'] = $urlNode->length > 0 ? $urlNode->item(0)->getAttribute('href') : 'N/A';

    // Format the extracted data
    $output = "Background Image URL: " . $couponData['background_image_url'] . PHP_EOL;
    $output .= "Image URL: " . $couponData['image_url'] . PHP_EOL;
    $output .= "Title: " . $couponData['title'] . PHP_EOL;
    $output .= "Description: " . $couponData['description'] . PHP_EOL;
    $output .= "Coupon Code: " . $couponData['coupon_code'] . PHP_EOL;
    $output .= "Book URL: " . $couponData['book_url'] . PHP_EOL;
    $output .= str_repeat("-", 40) . PHP_EOL;

    // Append to the file
    file_put_contents($outputFile, $output, FILE_APPEND);
}

echo "Coupons have been successfully saved to $outputFile.";
?>

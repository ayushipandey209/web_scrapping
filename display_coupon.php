<?php

$coupons = file_get_contents("coupons.txt");
$couponBlocks = explode("----------------------------------------", $coupons);

echo "<style>
    .coupon-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .coupon-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        height: 240px;
    }
    .coupon-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    .deal-tag {
        position: absolute;
        top: 16px;
        left: 16px;
        background: #1a1a1a;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .coupon-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        color: white;
    }
    .coupon-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .coupon-description {
        font-size: 14px;
        margin-bottom: 16px;
        opacity: 0.9;
    }
    .coupon-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .coupon-code {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 12px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .copy-icon {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .book-now-button {
        background: #FF5722;
        color: white;
        padding: 8px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.2s ease;
    }
    .book-now-button:hover {
        background: #F4511E;
    }
    .background-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
    }
</style>

<div class='coupon-container'>";

foreach ($couponBlocks as $coupon) {
    if (empty(trim($coupon))) continue;

    preg_match('/Background Image URL: (.*?)\n/', $coupon, $backgroundImage);
    preg_match('/Title: (.*?)\n/', $coupon, $title);
    preg_match('/Description: (.*?)\n/', $coupon, $description);
    preg_match('/Coupon Code: (.*?)\n/', $coupon, $couponCode);
    preg_match('/Book URL: (.*?)\n/', $coupon, $bookUrl);

    $backgroundImageUrl = $backgroundImage[1] ?? '';
    $couponTitle = $title[1] ?? '';
    $couponDescription = $description[1] ?? '';
    $couponCodeText = $couponCode[1] ?? '';
    $bookNowUrl = $bookUrl[1] ?? '#';
    
    $dealType = stripos($couponTitle, 'weekend') !== false ? 'WEEKEND SPECIAL' : 'LAST MINUTE DEAL';

    echo "
        <div class='coupon-card'>
            <img src='$backgroundImageUrl' alt='$couponTitle' class='background-image'>
            <div class='deal-tag'>$dealType</div>
            <div class='coupon-content'>
                <h2 class='coupon-title'>$couponTitle</h2>
                <p class='coupon-description'>$couponDescription</p>
                <div class='coupon-footer'>
                    <div class='coupon-code'>
                        $couponCodeText
                        <svg class='copy-icon' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
                            <path d='M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2v-2M8 4v12a2 2 0 002 2h8a2 2 0 002-2V8l-4-4H10a2 2 0 00-2 2z'/>
                        </svg>
                    </div>
                    <a href='$bookNowUrl' class='book-now-button'>BOOK NOW</a>
                </div>
            </div>
        </div>
    ";
}

echo "</div>";
?>
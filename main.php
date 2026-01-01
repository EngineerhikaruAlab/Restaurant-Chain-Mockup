<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use Models\RestaurantChain;

$chain = RestaurantChain::RandomGenerator();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Chain Management System</title>
    <style>
        /* 見た目を整えるためのシンプルなCSS */
        body { font-family: sans-serif; background-color: #f4f4f9; color: #333; line-height: 1.6; padding: 20px; }
        .chain-container { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 30px; max-width: 1000px; margin: auto; }
        .company-card { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .location-card { border-left: 5px solid #007bff; background: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 0 5px 5px 0; }
        .employee-card { display: inline-block; width: 250px; background: white; border: 1px solid #ddd; padding: 10px; margin: 10px; vertical-align: top; border-radius: 5px; font-size: 0.9em; }
        .employee-list { display: flex; flex-wrap: wrap; }
        h1 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h2 { color: #343a40; margin-top: 30px; }
        h3 { color: #495057; border-bottom: 1px dashed #ced4da; }
        p { margin: 5px 0; }
    </style>
</head>
<body>

    <?php
    // 3. HTMLとして表示
    echo $chain->toHTML();
    ?>

</body>
</html>
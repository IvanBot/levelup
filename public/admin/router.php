<?php
require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App();



require __DIR__ . '/../../src/admin/routes.php';

// Run app
$app->run();
?>
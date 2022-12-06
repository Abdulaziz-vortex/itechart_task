<?php
// This is psr based framework by Makhmudov Abdulaziz 2022


ini_set('display_errors', 1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use App\Handlers\FormHandler;
use Framework\Application\Application;
use Framework\Application\Singletone;

/*
 * @var $req Request
 */

// initialization
$req = \Framework\Http\RequestFactory::fromGlobals();

$request = $req->setHeader('x-Developer', 'Abdulaziz')->setHeader('protocol', "HTTP");

//==================== Application

//config file
$config = include 'config/main.php';

// #Application object -> entry point


$app = new Application($config);

Singletone::install($app);

//App init point

$app->init();

// pubsub component use subscribe

Singletone::$app->pubsub->subscribe('FORM_RECEIVED', (new FormHandler));


if (isset($_POST['form'])) {
    Singletone::$app->pubsub->publish('FORM_RECEIVED', Singletone::$app->request->post('*'));
}elseif (isset($_GET['delete_item'])){
    Singletone::$app->pubsub->publish('DELETE-ITEM', Singletone::$app->request->get('delete_item'));
}

Singletone::$app->redis->connect('localhost', 6379);

$keys = Singletone::$app->redis->keys('*');

$data = [];

foreach ($keys as $v) {
    $data[$v] = Singletone::$app->redis->get($v);
}

?>


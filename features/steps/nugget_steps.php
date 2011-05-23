<?php
$steps->Given('/^a nugget "([^"]*)"$/', function($world, $class) {
    require_once dirname(dirname(__FILE__)) . '/support/test_nugget_controller.php';
    $world->sut = new TestNuggetController();
});
$steps->Then('/^the module path should equal "([^"]*)"$/', function($world, $module_path) {
    Assert::Equals($module_path, $world->sut->module_path);
});

$steps->And('/^it should register routes$/', function($world) {
    $route = Router::parse($world->sut->module_path . '/some-resource');
    Assert::Equals('testnugget', $route['controller']);
    Assert::Equals('invoke', $route['action']);
});

$steps->And('/^it should register routes with parameters$/', function($world) {
    $route = Router::parse($world->sut->module_path . '/what/some-resource');
    Assert::Equals('testnugget', $route['controller']);
    Assert::Equals('invoke', $route['action']);
});

$steps->And('/^it should route the action$/', function($world) {
    $_SERVER['REQUEST_URI'] = $world->sut->module_path . '/what/some-resource';
    $_SERVER['REQUEST_METHOD'] = 'GET';

    $dispatcher = new Dispatcher();
    $result = $dispatcher->dispatch();
    Assert::Equals('what', $result->model);
});
$steps->And('/^it should route the action based on the verb$/', function($world) {
    $_SERVER['REQUEST_URI'] = $world->sut->module_path . '/what/some-resource';
    $_SERVER['REQUEST_METHOD'] = 'POST';

    $dispatcher = new Dispatcher();
    $result = $dispatcher->dispatch();
    Assert::Equals('what was posted', $result->model);
});
?>
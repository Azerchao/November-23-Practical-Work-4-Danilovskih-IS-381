<?php
declare(strict_types=1);

require_once __DIR__ . '/QueryBuilder.php';

echo "--- Запрос 1" . PHP_EOL;
echo QueryBuilder::table('users')
    ->select('id', 'name', 'email')
    ->where('age', '>=', 18)
    ->where('city', '=', 'Москва')
    ->orderBy('name')
    ->limit(10) . PHP_EOL;

echo "--- Запрос 2 (без select => *)" . PHP_EOL;
echo QueryBuilder::table('products')
    ->where('price', '<', 1000)
    ->orderBy('price', 'desc') . PHP_EOL;

echo "--- Запрос 3 (LIKE, строка с кавычкой)" . PHP_EOL;
echo QueryBuilder::table('books')
    ->select('title')
    ->where('title', 'LIKE', "О'Генри%")
    ->limit(5) . PHP_EOL;

echo "--- Запрос 4 (только таблица)" . PHP_EOL;
echo QueryBuilder::table('logs') . PHP_EOL;

echo "--- Запрос 5 (__call)" . PHP_EOL;
echo QueryBuilder::table('users')->whereCity('Москва')->whereFirstName('Анна') . PHP_EOL;

echo PHP_EOL . "--- Ошибки" . PHP_EOL;
$bad = [
    'Плохое направление' => fn() => QueryBuilder::table('t')->orderBy('id', 'UP'),
    'limit(0)'           => fn() => QueryBuilder::table('t')->limit(0),
    'Плохой оператор'    => fn() => QueryBuilder::table('t')->where('id', '<>', 1),
    'Неизвестный метод'  => fn() => QueryBuilder::table('t')->foo(),
];
foreach ($bad as $title => $fn) {
    try {
        $fn();
    } catch (InvalidArgumentException | BadMethodCallException $e) {
        echo "{$title}: " . get_class($e) . ' — ' . $e->getMessage() . PHP_EOL;
    }
}

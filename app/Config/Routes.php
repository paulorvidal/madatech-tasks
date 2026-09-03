<?php

$routes->get('/', 'TaskController::index');

$routes->group('tasks', function ($routes) {
    $routes->get('/', 'TaskController::index');             // Listar todas as tarefas
    $routes->get('new', 'TaskController::new');             // Exibir formulário de criação
    $routes->post('/', 'TaskController::create');           // Processar a criação
    $routes->get('(:num)/edit', 'TaskController::edit/$1'); // Exibir formulário de edição (pelo ID)
    $routes->put('(:num)', 'TaskController::update/$1');    // Processar a atualização 
    $routes->delete('(:num)', 'TaskController::delete/$1'); // Processar a exclusão
});

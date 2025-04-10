<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/web', function () use ($router) {
    return response()->json(["MetaFritterVersoWeb" => "Cesvi México"], 200);
});

// $router->group(['prefix' => '', 'middleware' => 'jwt'], function () use ($router) {
//     $router->get('',  ['uses' => '@index']); // Mostar todos los registros
//     $router->get('/{id}',  ['uses' => '@show']); // Muestra solo un registro
//     $router->post('}',  ['uses' => '@store']); // Crea un registro
//     $router->put('/{id}',  ['uses' => '@update']); // Actualiza un registro
//     $router->delete('/{id}',  ['uses' => '@destroy']); // Elimina un registro
// });

$router->group(['prefix' => 'service', ], function () use ($router) {
    $router->get('pokemon',  ['uses' => 'PokemonesController@index']); // Mostar todos los registros
});
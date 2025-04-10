<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/fritter', function () use ($router) {
    return response()->json(["MetaFritterVersoFritter" => "Cesvi México"], 200);
});

$router->group(['prefix' => 'menu', 'middleware' => 'jwt'], function () use ($router) { //'middleware' => 'jwt'
    
    $router->get('',  ['uses' => 'MenuDataController@index']);
    $router->get('/{id}', ['uses' => 'MenuDataController@show']);

    $router->get('/{id}/permisos',  ['uses' => 'MenuDataController@menuPermisos']);
    $router->get('/{id}/modulos',  ['uses' => 'MenuDataController@modulos']);
    $router->post('permisos',  ['uses' => 'MenuDataController@addPermiso']);
    $router->delete('permisos/{id}',  ['uses' => 'MenuDataController@deletePermiso']);
});

$router->group(['prefix' => 'user','middleware' => 'jwt'], function () use ($router) { //, 'middleware' => 'jwt'
    $router->get('',  ['uses' => 'UserDataController@index']);
    $router->get('/{id}', ['uses' => 'UserDataController@show']);
    $router->put('/{id}', ['uses' => 'UserDataController@update']);

    $router->post('/{id}/permisos',  ['uses' => 'UserDataController@menuPermisos']);
    $router->post('permisos',  ['uses' => 'UserDataController@addPermiso']);
    $router->delete('permisos/{id}',  ['uses' => 'UserDataController@deletePermiso']);
    

});

$router->group(['prefix' => 'configuracion','middleware' => 'jwt'], function () use ($router) { //, 'middleware' => 'jwt'
    $router->get('forms',  ['uses' => 'Configuracion\FormsController@showAll']);
    $router->post('forms',  ['uses' => 'Configuracion\FormsController@create']);
    $router->put('forms/{id}', ['uses' => 'Configuracion\FormsController@update']);
    $router->get('forms/combos',  ['uses' => 'Configuracion\FormsController@combos']);
    
    $router->get('forms/elements/{id}',  ['uses' => 'Configuracion\FormsController@showElement']);
    $router->delete('forms/elements/{id}',  ['uses' => 'Configuracion\FormsController@deleteElement']);

    $router->get('forms/elements/attributes/{id}',  ['uses' => 'Configuracion\FormsController@showAttributes']);
    $router->post('forms/elements/attributes',  ['uses' => 'Configuracion\FormsController@createAttributes']);
    $router->put('forms/elements/attributes',  ['uses' => 'Configuracion\FormsController@updateAttributes']);   
    
    $router->get('tables',  ['uses' => 'Configuracion\TablesController@showAll']);
    $router->post('tables',  ['uses' => 'Configuracion\TablesController@create']);
    $router->put('tables/{id}', ['uses' => 'Configuracion\TablesController@update']);
    
    $router->get('tables/columns/{id}',  ['uses' => 'Configuracion\TablesController@showColumn']);
    $router->delete('tables/columns/{id}',  ['uses' => 'Configuracion\TablesController@deleteElement']);

    $router->get('tables/columns/attributes/{id}',  ['uses' => 'Configuracion\TablesController@showAttributes']);
    $router->post('tables/columns/attributes',  ['uses' => 'Configuracion\TablesController@createAttributes']);
    $router->put('tables/columns/attributes',  ['uses' => 'Configuracion\TablesController@updateAttributes']); 
    
    $router->get('catalogs',  ['uses' => 'Configuracion\CatalogsController@showAll']);
    $router->get('catalogs/{id}',  ['uses' => 'Configuracion\CatalogsController@showCatalog']);
    $router->post('catalogs/{id}',  ['uses' => 'Configuracion\CatalogsController@create']);
    $router->put('catalogs/{idcatalog}/{idcolumn}', ['uses' => 'Configuracion\CatalogsController@update']);
    $router->delete('catalogs/{idcatalog}/{idcolumn}', ['uses' => 'Configuracion\CatalogsController@delete']);
    
});

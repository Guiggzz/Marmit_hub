<?php

// This file has been auto-generated by the Symfony Routing Component.

return [
    '_preview_error' => [['code', '_format'], ['_controller' => 'error_controller::preview', '_format' => 'html'], ['code' => '\\d+'], [['variable', '.', '[^/]++', '_format', true], ['variable', '/', '\\d+', 'code', true], ['text', '/_error']], [], [], []],
    'app_home' => [[], ['_controller' => 'App\\Controller\\HomeController::index'], [], [['text', '/']], [], [], []],
    'app_ingredient_nouvelle' => [[], ['_controller' => 'App\\Controller\\IngredientController::nouvelleIngredient'], [], [['text', '/creation/ingredient']], [], [], []],
    'app_ajouter_ingredient_recette' => [['recetteId'], ['_controller' => 'App\\Controller\\IngredientController::ajouterIngredientRecette'], [], [['variable', '/', '[^/]++', 'recetteId', true], ['text', '/ajouter-ingredient-a-recette']], [], [], []],
    'app_recette_nouvelle' => [[], ['_controller' => 'App\\Controller\\RecetteController::nouvelleRecette'], [], [['text', '/recette/nouvelle']], [], [], []],
    'recette_show' => [['id'], ['_controller' => 'App\\Controller\\RecetteController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/recette']], [], [], []],
    'app_register' => [[], ['_controller' => 'App\\Controller\\RegistrationController::register'], [], [['text', '/register']], [], [], []],
    'app_login' => [[], ['_controller' => 'App\\Controller\\SecurityController::login'], [], [['text', '/login']], [], [], []],
    'app_logout' => [[], ['_controller' => 'App\\Controller\\SecurityController::logout'], [], [['text', '/logout']], [], [], []],
    'App\Controller\HomeController::index' => [[], ['_controller' => 'App\\Controller\\HomeController::index'], [], [['text', '/']], [], [], []],
    'App\Controller\IngredientController::nouvelleIngredient' => [[], ['_controller' => 'App\\Controller\\IngredientController::nouvelleIngredient'], [], [['text', '/creation/ingredient']], [], [], []],
    'App\Controller\IngredientController::ajouterIngredientRecette' => [['recetteId'], ['_controller' => 'App\\Controller\\IngredientController::ajouterIngredientRecette'], [], [['variable', '/', '[^/]++', 'recetteId', true], ['text', '/ajouter-ingredient-a-recette']], [], [], []],
    'App\Controller\RecetteController::nouvelleRecette' => [[], ['_controller' => 'App\\Controller\\RecetteController::nouvelleRecette'], [], [['text', '/recette/nouvelle']], [], [], []],
    'App\Controller\RecetteController::show' => [['id'], ['_controller' => 'App\\Controller\\RecetteController::show'], [], [['variable', '/', '[^/]++', 'id', true], ['text', '/recette']], [], [], []],
    'App\Controller\RegistrationController::register' => [[], ['_controller' => 'App\\Controller\\RegistrationController::register'], [], [['text', '/register']], [], [], []],
    'App\Controller\SecurityController::login' => [[], ['_controller' => 'App\\Controller\\SecurityController::login'], [], [['text', '/login']], [], [], []],
    'App\Controller\SecurityController::logout' => [[], ['_controller' => 'App\\Controller\\SecurityController::logout'], [], [['text', '/logout']], [], [], []],
];

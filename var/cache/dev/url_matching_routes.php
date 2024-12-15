<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/' => [[['_route' => 'app_home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/creation/ingredient' => [[['_route' => 'app_ingredient_nouvelle', '_controller' => 'App\\Controller\\IngredientController::nouvelleIngredient'], null, null, null, false, false, null]],
        '/recette/nouvelle' => [[['_route' => 'app_recette_create', '_controller' => 'App\\Controller\\RecetteController::create'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/ingredient/([^/]++)(*:62)'
                .'|/recette/([^/]++)(*:86)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        62 => [[['_route' => 'app_ingredient_show', '_controller' => 'App\\Controller\\IngredientController::show'], ['id'], ['GET' => 0], null, false, true, null]],
        86 => [
            [['_route' => 'recette_show', '_controller' => 'App\\Controller\\RecetteController::show'], ['id'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

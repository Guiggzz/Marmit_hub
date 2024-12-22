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
        '/ingredients' => [
            [['_route' => 'app_ingredients_list', '_controller' => 'App\\Controller\\IngredientController::listIngredients'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_ingredient_list', '_controller' => 'App\\Controller\\IngredientController::list'], null, null, null, false, false, null],
        ],
        '/recette/nouvelle' => [[['_route' => 'app_recette_create', '_controller' => 'App\\Controller\\RecetteController::create'], null, null, null, false, false, null]],
        '/search' => [[['_route' => 'app_recipe_search', '_controller' => 'App\\Controller\\RecipeController::search'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\RegistrationController::register'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/ingredient/([^/]++)(?'
                    .'|(*:65)'
                    .'|/(?'
                        .'|update(*:82)'
                        .'|edit(*:93)'
                    .')'
                .')'
                .'|/recette/(?'
                    .'|([^/]++)(*:122)'
                    .'|supprimer/([^/]++)(*:148)'
                    .'|([^/]++)/edit(*:169)'
                .')'
                .'|/commentaire/(?'
                    .'|add/([^/]++)(*:206)'
                    .'|delete/([^/]++)(*:229)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        65 => [
            [['_route' => 'app_ingredient_show', '_controller' => 'App\\Controller\\IngredientController::show'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'ingredient_delete', '_controller' => 'App\\Controller\\IngredientController::delete'], ['id'], ['POST' => 0, 'DELETE' => 1], null, false, true, null],
        ],
        82 => [[['_route' => 'app_ingredient_update', '_controller' => 'App\\Controller\\RecetteController::update'], ['id'], null, null, false, false, null]],
        93 => [[['_route' => 'ingredient_edit', '_controller' => 'App\\Controller\\IngredientController::edit'], ['id'], null, null, false, false, null]],
        122 => [[['_route' => 'recette_show', '_controller' => 'App\\Controller\\RecetteController::show'], ['id'], null, null, false, true, null]],
        148 => [[['_route' => 'recette_delete', '_controller' => 'App\\Controller\\RecetteController::delete'], ['id'], ['POST' => 0], null, false, true, null]],
        169 => [[['_route' => 'recette_edit', '_controller' => 'App\\Controller\\RecetteController::edit'], ['id'], null, null, false, false, null]],
        206 => [[['_route' => 'commentaire_add', '_controller' => 'App\\Controller\\CommentaireController::addCommentaire'], ['recetteId'], ['POST' => 0], null, false, true, null]],
        229 => [
            [['_route' => 'commentaire_delete', '_controller' => 'App\\Controller\\CommentaireController::deleteCommentaire'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];

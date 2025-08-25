<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'controllers/home' => [
        'path' => './assets/controllers/home.js',
        'entrypoint' => true,
    ],
    'controllers/registration' => [
        'path' => './assets/controllers/registration.js',
        'entrypoint' => true,
    ],
    'controllers/authentication' => [
        'path' => './assets/controllers/authentication.js',
        'entrypoint' => true,
    ],
    'controllers/all-products' => [
        'path' => './assets/controllers/all-products.js',
        'entrypoint' => true,
    ],
    'controllers/error-404' => [
        'path' => './assets/controllers/error-404.js',
        'entrypoint' => true,
    ],
    'controllers/detailed-product' => [
        'path' => './assets/controllers/detailed-product.js',
        'entrypoint' => true,
    ],
    'controllers/back-office' => [
        'path' => './assets/controllers/back-office.js',
        'entrypoint' => true,
    ],
    'controllers/cart' => [
        'path' => './assets/controllers/cart.js',
        'entrypoint' => true,
    ],
];

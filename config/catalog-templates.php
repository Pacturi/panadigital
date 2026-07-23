<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Plantilla por defecto del catálogo público
    |--------------------------------------------------------------------------
    */
    'default' => 'basica',

    /*
    |--------------------------------------------------------------------------
    | Plantillas disponibles
    |--------------------------------------------------------------------------
    |
    | La clave debe coincidir con el nombre de la carpeta en:
    | - resources/views/public-templates/{clave}/
    |
    | Cada plantilla tiene:
    | - "file": inicio + detalle de producto (ej: templateBasica, templateAvanzada)
    | - "file_products": listado con filtros (ej: templateBasicaProducts)
    |
    | Para sumar una plantilla nueva: crear la carpeta + ambos archivos + agregar una entrada aquí.
    |
    */
    'available' => [
        'basica' => [
            'label' => 'Básica',
            'description' => 'Catálogo limpio y mobile-first, ideal para compartir por WhatsApp.',
            'file' => 'templateBasica',
            'file_products' => 'templateBasicaProducts',
        ],
        'avanzada' => [
            'label' => 'Avanzada',
            'description' => 'Inicio con hero, listado de productos con filtros, marcas y contacto.',
            'file' => 'templateAvanzada',
            'file_products' => 'templateAvanzadaProducts',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Slugs reservados (no pueden usarse como slug de negocio)
    |--------------------------------------------------------------------------
    */
    'reserved_slugs' => [
        'app',
        'dev',
        'login',
        'logout',
        'register',
        'dashboard',
        'password',
        'forgot-password',
        'reset-password',
        'verify-email',
        'confirm-password',
        'storage',
        'templates',
        'livewire',
        'filament',
        'up',
        'api',
    ],

];

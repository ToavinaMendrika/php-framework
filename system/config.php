<?php

use Framework\Router\Router;

return [
    'db.host' => 'localhost',
    'db.port' => 3336,
    'app.controller' => '\App\Controllers',
    'app.router' => str_replace('/',DIRECTORY_SEPARATOR, ROOT_DIRECTORY.'app/routes/web.php')
];
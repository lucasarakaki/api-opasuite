<?php

date_default_timezone_set('America/Fortaleza');
ini_set('max_execution_time', '300');

// Require autoload do composer e funções
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/functions/helpers.php';

// Depêndencias
use \Dotenv\Dotenv;

// Carrega as variáveis de ambiente no .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

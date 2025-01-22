<?php

require __DIR__ . '/../bootstrap.php';

use app\classes\OpaSuite;

$filter = [];

$options = [];

$id = '';

try {
    $opasuite = new OpaSuite();
    /* $data = $opasuite->get('', $filter, $options);

    dd($data); */
} catch (\Exception $e) {
    dd($e->getMessage());
}

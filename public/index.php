<?php

require __DIR__ . '/../bootstrap.php';

use app\classes\OpaSuite;

$filter = [
    "dataInicialAbertura"   => "2025-01-29",
    "dataFinalEncerramento" => "2025-01-29",
];

$options = [
    /* "limit" => 10, // Limite comentado, pode ser removido se não for necessário */
];

try {
    $opasuite          = new OpaSuite();
    $usuarios          = $opasuite->get('usuario', ['tipo' => 'user']);
    $atendimentos      = $opasuite->get('atendimento', $filter, $options);
    $atendimentos_data = $opasuite->addAdditionalInfo($atendimentos, $usuarios);

    // Extraindo os dados da API
    $data = array_map('extract_fields', $atendimentos_data['data']);

    // Guardando os dados em um arquivo .xlsx
    include __DIR__ . '/../includes/xlsx.php';

} catch (\Exception $e) {
    // Debug
    dd($e->getMessage());
}

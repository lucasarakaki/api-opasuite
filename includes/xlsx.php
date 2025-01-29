<?php

// Dependências
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$file = __DIR__ . '/../files/opasuite.xlsx';

// Verifica se existe um arquivo .xlsx e captura a ultima linha preenchida,
if (file_exists($file)) {
    $spreadsheet = IOFactory::load($file);
    $sheet       = $spreadsheet->getActiveSheet();

    $last_row = $sheet->getHighestRow();
} else {
// Caso não exista, um novo arquivo é criado.
    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();

    $headers = [
        'Inicio do atendimento', 'Fim do atendimento', 'Protocolo', 'Departamento', 'Observação', 'Cliente', 'Atendente', 'Nota do atendente', 'Nota da empresa',
    ];

    $sheet->fromArray($headers, null, 'A1');
    $last_row = 1;
}

$row = $last_row + 1;
foreach ($data as $item) {
    if ($item['atendente'] != 'bot') {
        $sheet->setCellValue('A' . $row, $item['date_start']);
        $sheet->setCellValue('B' . $row, $item['date_end']);
        $sheet->setCellValue('C' . $row, $item['protocolo']);
        $sheet->setCellValue('D' . $row, $item['departamento']);
        $sheet->setCellValue('E' . $row, ! isset($item['observacoes'][0]) ? 'sem observação' : $item['observacoes'][0]['mensagem']);
        $sheet->setCellValue('F' . $row, $item['cliente']);
        $sheet->setCellValue('G' . $row, $item['atendente']);
        $sheet->setCellValue('H' . $row, ! isset($item['avaliacoes'][0]) ? 'sem avaliação' : $item['avaliacoes'][0]['nota']);
        $sheet->setCellValue('I' . $row, ! isset($item['avaliacoes'][1]) ? 'sem avaliação' : $item['avaliacoes'][1]['nota']);
        $row++;
    }
}

$writer = new Xlsx($spreadsheet);
$writer->save($file);

dd('Dados inseridos no arquivo: ' . ltrim($file, __DIR__));

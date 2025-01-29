<?php

/**
 * Extraí os dados retornando pela API
 * @param mixed $item
 *
 * @return array
 */
function extract_fields($item): array
{
    return [
        'descricacao'  => $item['descricao'],
        'protocolo'    => $item['protocolo'],
        'tags'         => $item['tags'],
        'avaliacoes'   => $item['avaliacoes'],
        'observacoes'  => $item['observacoes'],
        'motivos'      => $item['motivos'],
        'atendente'    => $item['atendente'],
        'cliente'      => $item['cliente'],
        'departamento' => $item['departamento'],
        'date_start'   => $item['date_start'],
        'date_end'     => $item['date_end'],
    ];
}

/**
 * @param string $date Data para converter
 *
 * @return string Data convertida
 */
function convert_date(string $date): string
{
    $datetime = new DateTime($date);

    $timezone = new DateTimeZone('America/Fortaleza');

    $datetime->setTimezone($timezone);

    return $datetime->format('d/m/Y H:i');
}

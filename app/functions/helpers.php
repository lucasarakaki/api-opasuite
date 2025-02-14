<?php

/**
 * Extraí os dados retornando pela API
 * @param mixed $item
 *
 * @return array<mixed>
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
 * @param string $format Formato da data
 *
 * @return string
 */
function convert_date(string $date, string $format): string
{
    $datetime = new DateTime($date);

    $timezone = new DateTimeZone('America/Fortaleza');

    $datetime->setTimezone($timezone);

    return $datetime->format($format);
}

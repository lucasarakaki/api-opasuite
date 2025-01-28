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
    ];
}

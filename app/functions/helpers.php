<?php

/**
 * Vai localizar o nome do atendente
 * @param array $usuarios Array com os dados dos usuários do Opa Suite
 * @param string $id ID do atendente que deseja encontrar
 *
 * @return string Nome do atendente, ou mensagem de aviso.
 */
function get_username(array $usuarios, string $id): string
{
    foreach ($usuarios['data'] as $usuario) {
        if ($usuario['_id'] === $id) {
            return $usuario['nome'];
        }
    }

    return 'Atendente não encontrado!';
}

/**
 * Vai localizar o nome do departamento/setor
 * @param object $opasuite Instância da classe OpaSuite
 * @param string $id ID do departamento que deseja encontrar
 *
 * @return string Nome do deparramento, ou mensagem de aviso.
 */
function get_dept_name(object $opasuite, string $id): string
{
    $departamento_data = $opasuite->getById('departamento', $id);

    return $departamento_data['data']['nome'] ?? 'Departamento não encontrado!';
}

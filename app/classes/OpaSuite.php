<?php
namespace app\classes;

use Exception;
use InvalidArgumentException;

class OpaSuite
{
    private $apiKey;
    private $baseUrl;

    /**
     * Vai armazenar a apiKey e baseUrl de acordo com as variáveis
     * de ambiente no .env, e caso estiver vazio retorna uma exception.
     */
    public function __construct()
    {
        $this->apiKey  = $_ENV['API_KEY'] ?? '';
        $this->baseUrl = $_ENV['BASE_URL'] ?? '';

        if (empty($this->apiKey) || empty($this->baseUrl)) {
            throw new Exception("Chave da API ou URL não definida.");
        }
    }

    /**
     * Realiza uma requisição get na api.
     * @param string $endpoint Endpoint da API
     * @param array $filter Definição de filtros https://api.opasuite.com.br/#intro
     * @param array $options Opções (skip e limit)
     *
     * @return array Retorna a resposta da API em formato de array.
     */
    public function get(string $endpoint, array $filter = [], array $options = []): array
    {
        // Url + endpoint da api
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        // Guarda o filters e options em um array
        $post_fields = [
            'filter'  => $filter,
            'options' => $options,
        ];

        // Converte para json
        $json_post_fields = json_encode($post_fields);

        // Inicia o curl
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_POSTFIELDS     => $json_post_fields,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        // Verifica se ocorreu um erro
        if (curl_errno($curl)) {
            throw new Exception('Error curl: ' . curl_error($curl));
        }

        // Fecha o curl
        curl_close($curl);

        return strlen($response) ? json_decode($response, true) : [];
    }

    /**
     * @param string $endpoint Endpoint da API
     * @param string $id ID inserido na URL
     *
     * @return array Retorna a resposta da API em formato de array.
     */
    private function getById(string $endpoint, string $id): array
    {
        // Url + endpoint da api
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/') . '/' . ltrim($id, '/');

        // Inicia o curl
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        // Verifica se ocorreu um erro
        if (curl_errno($curl)) {
            throw new Exception('Error curl: ' . curl_error($curl));
        }

        // Fecha o curl
        curl_close($curl);

        return strlen($response) ? json_decode($response, true) : [];
    }

    /**
     * Método reponsável por iterar os dados 'atendente', 'departamento' e 'cliente'
     * @param array $atendimentos Array com os dados de atendimentos retornados da API
     * @param array $usuarios Array com os dados de usuários retornados da API
     *
     * @return array Array com os novos dados
     */
    public function addAdditionalInfo(array $atendimentos, array $usuarios): array
    {
        if (! isset($atendimentos['data']) || ! is_array($atendimentos['data'])) {
            throw new InvalidArgumentException("A chave 'data' não existe ou não é um array.");
        }

        foreach ($atendimentos['data'] as &$atendimento) {
            $id_atendimento = $atendimento['_id'];
            $id_atendente   = $atendimento['id_atendente'];
            $id_dept        = $atendimento['setor'];
            $date_start     = $atendimento['date'];
            $date_end       = $atendimento['fim'];

            $atendente_nome    = $this->getUsername($usuarios, $id_atendente);
            $cliente_nome      = $this->getClientName($id_atendimento);
            $departamento_nome = $this->getDeptName($id_dept);
            $date_start        = convert_date($date_start, 'd/m/Y H:i');
            $date_end          = convert_date($date_end, 'd/m/Y H:i');

            $atendimento['atendente']    = $atendente_nome;
            $atendimento['cliente']      = $cliente_nome;
            $atendimento['departamento'] = $departamento_nome;
            $atendimento['date_start']   = $date_start;
            $atendimento['date_end']     = $date_end;
        }

        return $atendimentos;
    }

    /**
     * Método responsável por localizar o nome do atendente
     * @param array $usuarios Array com os dados dos usuários do Opa Suite
     * @param string $id ID do atendente que deseja encontrar
     *
     * @return string Nome do atendente, ou mensagem de aviso.
     */
    private function getUsername(array $usuarios, string $id): string
    {
        if (! isset($usuarios['data']) || ! is_array($usuarios['data'])) {
            throw new InvalidArgumentException("A chave 'data' não existe ou não é um array.");
        }

        foreach ($usuarios['data'] as $usuario) {
            if ($usuario['_id'] === $id) {
                return $usuario['nome'];
            }
        }

        return 'bot';
    }

    /**
     * Vai localizar o nome do departamento/setor
     * @param string $id ID do departamento que deseja encontrar
     *
     * @return string Nome do departamento, ou mensagem de aviso.
     */
    private function getDeptName(string $id): string
    {
        $departamento_data = $this->getById('departamento', $id);

        return $departamento_data['data']['nome'] ?? 'Departamento não encontrado!';
    }

    /**
     * Vai localizar o nome do cliente
     * @param string $id ID do cliente que deseja encontrar
     *
     * @return string Nome do cliente, ou mensagem de aviso.
     */
    private function getClientName(string $id): string
    {
        $cliente_data = $this->getById('atendimento', $id);

        return $cliente_data['data']['id_user']['nome'] ?? 'Cliente não encontrado!';
    }
}

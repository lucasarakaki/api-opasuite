<?php
namespace app\classes;

use Exception;

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
    public function getById(string $endpoint, string $id): array
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
}

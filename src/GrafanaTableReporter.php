<?php

namespace Robocash\GrafanaTableReporter;

use Carbon\CarbonInterface;
use GuzzleHttp\Client;

class GrafanaTableReporter
{
    private $baseUrl;
    private $apiToken;
    /** @var Client $guzzle */
    private $guzzle;

    /**
     * GrafanaTableReporter constructor.
     * @param $baseUrl
     * @param $apiToken
     */
    public function __construct(string $baseUrl, string $apiToken)
    {
        $this->baseUrl = $baseUrl;
        $this->apiToken = $apiToken;
        $this->guzzle = new Client();
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     */
    public function setApiToken(string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    private function getSql(string $dashboardId, int $panelId): string
    {
        $response = $this->guzzle->get($this->getBaseUrl() . '/api/dashboards/uid/' . $dashboardId, [
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getApiToken()
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        $index = array_search($panelId, array_column($data['dashboard']['panels'], 'id'), true);

        return $data['dashboard']['panels'][$index]['targets'][0]['rawSql'];
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @param string $dashboardId
     * @param int $panelId
     * @param string $format
     * @return array|resource
     */
    public function getData(CarbonInterface $from, CarbonInterface $to, string $dashboardId, int $panelId, string $format = 'csv-resource')
    {
        $response = $this->guzzle->post($this->getBaseUrl() . '/api/tsdb/query/', [
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getApiToken(),
                'Content-Type' => 'application/json;charset=UTF-8'
            ],
            'body' => json_encode(
                [
                    'from' => (string)(($from->getTimestamp() + $from->getOffset()) * 1000), // получаем timestamp с учетом часового пояса
                    'to' => (string)(($to->getTimestamp() + $from->getOffset()) * 1000 + 999),
                    'queries' =>
                        [
                            0 =>
                                [
                                    'refId' => 'A',
                                    'datasourceId' => 1,
                                    'rawSql' => $this->getSql($dashboardId, $panelId),
                                    'format' => 'table',
                                ],
                        ],
                ]
            )

        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $table = $data['results']['A']['tables'][0];

        if ($format === 'csv-resource') {
            return $this->buildCsv($table);
        }

        return $table;
    }


    /**
     * @param array $data
     * @return resource
     */
    private function buildCsv(array $data)
    {
        $csv = fopen('php://memory', 'wb+');

        fputcsv($csv, array_column($data['columns'], 'text'));

        foreach ($data['rows'] as $row) {
            fputcsv($csv, $row);
        }
        rewind($csv);

        return $csv;
    }
}
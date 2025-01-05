<?

namespace Theafolayan\ListmonkLaravel\Http;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    protected $client;

    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('listmonk.base_url'),
            'auth' => [config('listmonk.username'), config('listmonk.password')],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function request($method, $endpoint, $data = [])
    {
        $response = $this->client->request($method, $endpoint, ['json' => $data]);
        return json_decode($response->getBody(), true);
    }
}

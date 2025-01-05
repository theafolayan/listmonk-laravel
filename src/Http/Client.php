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
            'headers' => [
                'Authorization' => 'token ' . config('listmonk.api_user') . ':' . config('listmonk.api_token'),
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

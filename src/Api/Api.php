<?php
/**
 * Created by PhpStorm.
 * User: jasser
 * Date: 27/05/22
 * Time: 15:55
 */

namespace App\Api;

use App\ApiManager\ApiManager;
use App\Entity\Product;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
    /**
     * @var HttpClient
     */
    private $client;
    /**
     * @var ApiManager
     */
    private $apiManager;

    public function __construct(HttpClientInterface $client, ApiManager $apiManager)
    {
        $this->client = $client;
        $this->apiManager = $apiManager;
    }

    public function getFoodInformation()
    {
        $response  = $this->apiManager->get(
            'https://world.openfoodfacts.org/api/v0/product/737628064502.json',
            [
            ],
            [],
            Product::class
        );
        return $response;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 02.11.2018
 * Time: 23:40
 */

namespace App\Services;


use App\Models\Link;
use App\Repositories\LinkRepository;
use App\Utils\RandomGenerator;
use Exception;
use GuzzleHttp\Client;

class LinkService
{
    /**
     * @var LinkRepository
     */
    private $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function create($rootUrl, $baseUrl, $expiryDate)
    {
        if (!$this->isRootUrlCorrect($rootUrl)) {
            return false;
        }

        $shortUrl = $this->generateShortUrl($baseUrl);
        $this->linkRepository->create($rootUrl, $shortUrl, $expiryDate);

        return $shortUrl;
    }

    private function isRootUrlCorrect($rootUrl)
    {
        $client = new Client();

        try {
            $request = $client->head($rootUrl);
        } catch (Exception $e) {
            return false;
        }

        if ($request->getStatusCode() == 200) {
            return true;
        }

        return false;
    }

    private function generateShortUrl($baseUrl)
    {
        //TODO check here if unique code is already exist in db and regenerate if true
        $uniqueCode = RandomGenerator::generateCode();
        $shortUrl = $baseUrl . '/' . $uniqueCode;

        return $shortUrl;
    }

    public function delete()
    {

    }
}
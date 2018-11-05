<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 02.11.2018
 * Time: 23:40
 */

namespace App\Services;


use App\Exceptions\ShortUrlAlreadyExistsException;
use App\Exceptions\UrlDoesNotExistException;
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

    /**
     * @param $rootUrl
     * @param $customUrl
     * @param $expiryDate
     * @return string
     * @throws ShortUrlAlreadyExistsException
     * @throws UrlDoesNotExistException
     */
    public function create($rootUrl, $customUrl, $expiryDate)
    {
//        if (!$this->isRootUrlCorrect($rootUrl)) {
//            throw new UrlDoesNotExistException();
//        }

        if ($customUrl != null) {
            if ($this->linkRepository->isShortLinkExists($customUrl)) {
                throw new ShortUrlAlreadyExistsException();
            }

            $shortUrl = $customUrl;
        } else {
            $shortUrl = $this->generateShortUrl();
        }

        $this->linkRepository->create($rootUrl, $shortUrl, $expiryDate);

        return $shortUrl;
    }

    /*
     * Try to recognize is root url is correct.
     */
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

    private function generateShortUrl()
    {
        //not the best way to check unique, I know
        while (true) {
            $shortUrl = RandomGenerator::generateCode();

            if (!$this->linkRepository->isShortLinkExists($shortUrl))
                break;
        }

        return $shortUrl;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 03.11.2018
 * Time: 16:20
 */

namespace App\Repositories;


use App\Models\Link;

class LinkRepository
{
    public function create($rootUrl, $shortUrl, $expiryDate)
    {
        $link = new Link();
        $link->root_url = $rootUrl;
        $link->short_url = $shortUrl;
        $link->expiry_date = $expiryDate;
        $link->save();
    }

    public function isShortLinkExists($shortUrl)
    {
        $link = Link::where('short_url', $shortUrl)->first();

        return $link === null ? false : true;
    }
}
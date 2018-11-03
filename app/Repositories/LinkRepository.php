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
        $link->save();
    }
}
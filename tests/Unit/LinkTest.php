<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 09.12.2018
 * Time: 22:45
 */

namespace Tests\Unit;


use App\Models\Link;
use App\Repositories\LinkRepository;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testCreateLink(){
        $linkRepository = new LinkRepository();
        $linkRepository->create('test_root_url', 'test_short_url', null);


    }

}
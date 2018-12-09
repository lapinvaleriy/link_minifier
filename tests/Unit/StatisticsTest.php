<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 09.12.2018
 * Time: 22:52
 */

namespace Tests\Unit;


use App\Models\Link;
use App\Repositories\LinkRepository;
use App\Services\StatisticsService;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    /**
     * @test
     */
    public function send_short_url_with_no_link_to_get_data_by_short_url_method()
    {
        $doesNotExistsUrl = "doesnotexisturl";

        $statisticsService = new StatisticsService();
        $result = $statisticsService->getDataByShortUrl($doesNotExistsUrl);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('failed', $result['status']);
    }


    /**
     * @test
     */
    public function get_no_statistics_returned_by_get_data_by_short_url_method()
    {
        $testLink = new Link();
        $testLink->root_url = 'test_root_url';
        $testLink->short_url = 'test_short_url';
        $testLink->save();

        $statisticsService = new StatisticsService();
        $result = $statisticsService->getDataByShortUrl($testLink->short_url);

        Link::find($testLink->id)->delete();

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('failed', $result['status']);
        $this->assertEquals('Нет статистики для данной ссылки', $result['msg']);
    }
}
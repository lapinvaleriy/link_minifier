<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 13:52
 */

namespace App\Http\Controllers;


use App\Models\Link;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    private $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function showStatistics($url)
    {
        $link = Link::where('short_url', $url)->first();

        if ($link === null) {
            return view('error', [
                'error_msg' => 'Не существует статистики по данной ссылке'
            ]);
        }

        return view('stat');
    }

    public function getData(Request $request)
    {
        $request->validate([
            'short_url' => 'required',
        ]);

        $shortUrl = $request->short_url;

        return $this->statisticsService->getDataByShortUrl($shortUrl);
    }

    public function show()
    {
        return view('stat');
    }
}
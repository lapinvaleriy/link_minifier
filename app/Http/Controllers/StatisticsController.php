<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 13:52
 */

namespace App\Http\Controllers;


use App\Models\Link;
use App\Models\Statistics;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function info($url)
    {
        $link = Link::where('short_url', $url)->first();

        if ($link === null) {
            return view('error', [
                'error_msg' => 'Не существует статистики по данной ссылке'
            ]);
        }

        return view('stat', [
            'stat' => 'hello'
        ]);
    }

    public function show()
    {
        return view('stat');
    }

    public function getData(Request $request)
    {
        $shortUrl = $request->short_url;
        $link = Link::where('short_url', $shortUrl)->first();

        if ($link === null) {
            return [
                'status' => 'failed',
                'msg' => 'Такого url не существует'
            ];
        }

        $data = Statistics::select('country', 'language', 'browser', 'platform')
            ->where('link_id', $link->id)->get();

        if (empty($data)) {
            return [
                'status' => 'failed',
                'msg' => 'Нет статистики по данной ссылке'
            ];
        }

        foreach ($data as $dat) {
            $countries[] = $dat->country;
            $browsers[] = $dat->browser;
            $languages[] = $dat->language;
            $platforms[] = $dat->platform;
        }

        $count = count($data);
        $array = [$countries, $browsers, $languages, $platforms];

        for ($k = 0; $k < count($array); $k++) {
            for ($i = 0; $i < count($array[$k]); $i++) {
                $count = 0;
                $countr = $array[$k][$i];
                for ($j = 0; $j < count($array[$k]); $j++) {
                    if ($array[$k][$i] === $array[$k][$j])
                        $count++;
                }
                $result[$countr] = $count;
            }

            $array[$k] = [
                'labels' => array_keys($result),
                'count' => array_values($result)
            ];

            $result = [];
        }

        return [
            'result' => [
                'count' => $count,
                'countries' => $array[0],
                'browsers' => $array[1],
                'languages' => $array[2],
                'platforms' => $array[3],
            ]
        ];
    }
}
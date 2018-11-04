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

        for ($i = 0; $i < count($countries); $i++) {
            $count = 0;
            $countr = $countries[$i];
            for ($j = 0; $j < count($countries); $j++) {
                if ($countries[$i] === $countries[$j])
                    $count++;
            }
            $c[$countr] = $count;
        }

        for ($i = 0; $i < count($browsers); $i++) {
            $count = 0;
            $countr = $browsers[$i];
            for ($j = 0; $j < count($browsers); $j++) {
                if ($browsers[$i] === $browsers[$j])
                    $count++;
            }
            $b[$countr] = $count;
        }

        for ($i = 0; $i < count($languages); $i++) {
            $count = 0;
            $countr = $languages[$i];
            for ($j = 0; $j < count($languages); $j++) {
                if ($languages[$i] === $languages[$j])
                    $count++;
            }
            $l[$countr] = $count;
        }

        for ($i = 0; $i < count($platforms); $i++) {
            $count = 0;
            $countr = $platforms[$i];
            for ($j = 0; $j < count($platforms); $j++) {
                if ($platforms[$i] === $platforms[$j])
                    $count++;
            }
            $p[$countr] = $count;
        }

        return [
            'result' => [
                'countries' => [
                    'labels' => array_keys($c),
                    'count' => array_values($c)
                ],
                'languages' => [
                    'labels' => array_keys($l),
                    'count' => array_values($l)
                ],
                'browsers' => [
                    'labels' => array_keys($b),
                    'count' => array_values($b)
                ],
                'platforms' => [
                    'labels' => array_keys($p),
                    'count' => array_values($p)
                ],
            ]
        ];
    }
}
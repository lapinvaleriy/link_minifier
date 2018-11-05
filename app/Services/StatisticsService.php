<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 14:10
 */

namespace App\Services;


use App\Models\Link;
use App\Models\Statistics;
use App\Utils\LanguageResolver;

class StatisticsService
{
    public function addStat($link, $request)
    {
        $stat = new Statistics();
        $stat->country = $this->getCountryByIp($request);
        $stat->language = $this->getLanguage($request);
        $stat->browser = get_browser($request->header('User-Agent'))->browser;
        $stat->platform = get_browser($request->header('User-Agent'))->platform;
        $stat->link_id = $link->id;
        $stat->save();
    }

    private function getLanguage($request)
    {
        $reducedLang = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        return LanguageResolver::getLanguage($reducedLang);
    }

    private function getCountryByIp($request)
    {
        if ($request->ip === '127.0.0.1')
            return 'Ukraine';
        else
            $ipData = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $request->ip));

        return $ipData->geoplugin_countryName;
    }

    public function getDataByShortUrl($shortUrl)
    {
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
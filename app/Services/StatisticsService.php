<?php
/**
 * Created by PhpStorm.
 * User: skull
 * Date: 04.11.2018
 * Time: 14:10
 */

namespace App\Services;


use App\Models\Statistics;
use App\Utils\LanguageResolver;
use Jenssegers\Agent\Agent;

class StatisticsService
{
    public function addStat($link, $request)
    {
        $agent = new Agent();

        $stat = new Statistics();
        $stat->country = $this->getCountryByIp($request);
        $stat->language = $this->getLanguage($request);
        $stat->browser = $this->getBrowserName($request);
        $stat->platform = $this->getPlatform($request);
        $stat->link_id = $link->id;
        $stat->save();
    }

    private function getBrowserName($request)
    {
        return get_browser($request->header('User-Agent'))->browser;
    }

    private function getPlatform($request)
    {
        return get_browser($request->header('User-Agent'))->platform;
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
}
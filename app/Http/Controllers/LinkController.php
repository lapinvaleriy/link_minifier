<?php

namespace App\Http\Controllers;

use App\Exceptions\UrlMinifierException;
use App\Models\Link;
use App\Services\LinkService;
use App\Services\StatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LinkController extends Controller
{

    private $linkService;
    private $statisticsService;

    public function __construct(LinkService $linkService, StatisticsService $statisticsService)
    {
        $this->linkService = $linkService;
        $this->statisticsService = $statisticsService;
    }

    public function create(Request $request)
    {
        $request->validate([
            'root_url' => 'required|url',
        ]);

        $rootUrl = $request->root_url;
        $customUrl = $request->custom_url;
        $expiryDate = $request->expiry_date;

        try {
            $result = $this->linkService->create($rootUrl, $customUrl, $expiryDate);

            $shortUrl = $request->root() . '/' . $result;
            $statUrl = $request->root() . '/stat/' . $result;
            $status = 'success';
            $msg = 'Ссылка успешно сгенерирована';
        } catch (UrlMinifierException $e) {
            $shortUrl = null;
            $statUrl = null;
            $status = 'failed';
            $msg = $e->getMessage();
        }

        return [
            'status' => $status,
            'short_url' => $shortUrl,
            'stat_url' => $statUrl,
            'msg' => $msg
        ];
    }

    public function redirect(Request $request, $url)
    {
        $link = Link::where('short_url', $url)->first();

        if ($link === null) {
            return view('error', [
                'error_msg' => 'Такой ссылки не существует'
            ]);
        }

        if ($link->expiry_date !== null && $link->expiry_date < Carbon::now()) {
            return view('error', [
                'error_msg' => 'Срок действия ссылки истек'
            ]);
        }

        $this->statisticsService->addStat($link, $request);

        return redirect($link->root_url);
    }

    public function show()
    {
        return view('main');
    }
}

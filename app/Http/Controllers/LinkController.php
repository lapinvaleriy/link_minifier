<?php

namespace App\Http\Controllers;

use App\Services\LinkService;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * @var LinkService
     */
    private $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function create(Request $request)
    {
        $request->validate([
            'root_url' => 'required|max:255',
        ]);

        $rootUrl = $request->root_url;
//        $expiryDate = $request->$expiryDate

        $this->linkService->create();

    }


    public function delete(Request $request)
    {

    }

    public function minify(Request $request)
    {
        $url = $request->root_url;

        if ($url === 'hello') {
            return [
                'status' => 'success',
                'msg' => 'hello, how r u'
            ];
        }

        return [
            'status' => 'failed',
            'msg' => 'Такого url не существует'
        ];
    }
}

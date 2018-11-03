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
        $baseUrl = $request->root();

        $result = $this->linkService->create($rootUrl, $baseUrl, null);

        if ($result === false) {
            return [
                'status' => 'failed',
                'msg' => 'Такого url не существует'
            ];
        }

        return [
            'status' => 'success',
            'result' => $result,
            'msg' => 'Короткая ссылка успешно сгенерирована'
        ];
    }

    public function show()
    {
        $user = auth()->user();
        $userLinks = null;

        if ($user !== null) {
            $userLinks = $this->linkService->findUserLinks($user);
        }

        return view('main', [
            'user_links' => $userLinks
        ]);
    }

    public function delete(Request $request)
    {

    }
}

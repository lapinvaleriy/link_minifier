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


    }

    public function delete(Request $request)
    {

    }
}

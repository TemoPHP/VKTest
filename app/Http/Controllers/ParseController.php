<?php

namespace App\Http\Controllers;

use App\Jobs\GetDataFromVK;
use App\Service\VKService;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

class ParseController extends Controller
{
    /**
     * Запрос на получение данных
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'token'         => 'required',
                'method'        => 'required',
            ]);

            $VKService = new VKService($request->token, $request->params);
            $result = $VKService->shapingAndCallMethod($request->all()['method']);

        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'error_message' => $th->getMessage()]);
        }

        return response()->json($result);
    }
}

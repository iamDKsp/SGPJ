<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ProcessoController extends Controller
{
    public function index(): JsonResponse
    {
        $storagePath = Config::get('sgpj.processos_storage');
        $conteudo = [];

        if ($storagePath && Storage::exists($storagePath)) {
            try {
                $conteudo = json_decode(Storage::get($storagePath), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                $conteudo = [];
            }
        }

        if (!is_array($conteudo)) {
            $conteudo = [];
        }

        return response()->json($conteudo);
    }
}

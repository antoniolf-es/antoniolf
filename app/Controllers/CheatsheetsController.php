<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CheatsheetModel;

final class CheatsheetsController extends Controller
{
    public function index(): void
    {
        $cheatsheets = (new CheatsheetModel())->todos();

        if ($cheatsheets === []) {
            $this->mostrar404();
        }

        $this->redirigir('/cheatsheets/' . $cheatsheets[0]['slug']);
    }

    public function ver(string $slug): void
    {
        $modelo = new CheatsheetModel();
        $cheatsheet = $modelo->porSlug($slug);

        if ($cheatsheet === null) {
            $this->mostrar404();
        }

        $this->render('cheatsheets/ver', [
            'titulo' => $cheatsheet['titulo'],
            'cheatsheet' => $cheatsheet,
            'cheatsheets' => $modelo->todos(),
        ]);
    }
}

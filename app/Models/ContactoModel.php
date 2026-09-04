<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ContactoModel
{
    public function crear(string $nombre, string $email, string $asunto, string $mensaje): int
    {
        $consulta = Database::conexion()->prepare(
            'INSERT INTO contacts (name, email, subject, comment, created_at) VALUES (?, ?, ?, ?, NOW())'
        );

        $consulta->execute([$nombre, $email, $asunto, $mensaje]);

        return (int) Database::conexion()->lastInsertId();
    }
}

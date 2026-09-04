<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class UsuarioModel
{
    public function porEmail(string $email): ?array
    {
        $consulta = Database::conexion()->prepare(
            'SELECT id, name, email, password, role, active FROM users WHERE email = ?'
        );
        $consulta->execute([$email]);

        $fila = $consulta->fetch();

        return $fila === false ? null : [
            'id' => (int) $fila['id'],
            'nombre' => (string) $fila['name'],
            'email' => (string) $fila['email'],
            'password' => (string) $fila['password'],
            'role' => (int) $fila['role'],
            'active' => (int) $fila['active'],
        ];
    }
}

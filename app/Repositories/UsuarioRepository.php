<?php
namespace App\Repositories;

use PDO;
use App\Config\Cnn;
use App\Models\Usuario;

class UsuarioRepository
{
    private PDO $db;

    public function __construct()
    {
        $cnn = new Cnn();
        $this->db = $cnn->getConnection();
    }

    /** @return Usuario[] */
    public function findAll(): array
    {
        $sql = "SELECT id_usuario, nombre, apellido, email, password_hash,
                       tipo_usuario, estado, fecha_registro
                FROM usuarios
                ORDER BY apellido ASC, nombre ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $usuarios = [];
        foreach ($rows as $row) {
            $usuarios[] = new Usuario(
                (int)$row['id_usuario'],
                $row['nombre'],
                $row['apellido'],
                $row['email'],
                $row['password_hash'],
                $row['tipo_usuario'],
                (int)$row['estado'],
                $row['fecha_registro'] ?? null
            );
        }
        return $usuarios;
    }

    public function findById(int $id_usuario): ?Usuario
    {
        $sql = "SELECT id_usuario, nombre, apellido, email, password_hash,
                       tipo_usuario, estado, fecha_registro
                FROM usuarios
                WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_usuario]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Usuario(
            (int)$row['id_usuario'],
            $row['nombre'],
            $row['apellido'],
            $row['email'],
            $row['password_hash'],
            $row['tipo_usuario'],
            (int)$row['estado'],
            $row['fecha_registro'] ?? null
        );
    }

    public function findByEmail(string $email): ?Usuario
    {
        $sql = "SELECT id_usuario, nombre, apellido, email, password_hash,
                       tipo_usuario, estado, fecha_registro
                FROM usuarios
                WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Usuario(
            (int)$row['id_usuario'],
            $row['nombre'],
            $row['apellido'],
            $row['email'],
            $row['password_hash'],
            $row['tipo_usuario'],
            (int)$row['estado'],
            $row['fecha_registro'] ?? null
        );
    }

    public function create(Usuario $usuario): int
    {
        $sql = "INSERT INTO usuarios
                    (nombre, apellido, email, password_hash, tipo_usuario, estado)
                VALUES
                    (:nombre, :apellido, :email, :password_hash, :tipo_usuario, :estado)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nombre'        => $usuario->getNombre(),
            ':apellido'      => $usuario->getApellido(),
            ':email'         => $usuario->getEmail(),
            ':password_hash' => $usuario->getPasswordHash(),
            ':tipo_usuario'  => $usuario->getTipoUsuario(),
            ':estado'        => $usuario->getEstado()
        ]);

        $id = (int)$this->db->lastInsertId();
        $usuario->setIdUsuario($id);
        return $id;
    }

    public function update(Usuario $usuario): bool
    {
        if ($usuario->getIdUsuario() === null) {
            return false;
        }

        $sql = "UPDATE usuarios
                SET nombre        = :nombre,
                    apellido      = :apellido,
                    email         = :email,
                    password_hash = :password_hash,
                    tipo_usuario  = :tipo_usuario,
                    estado        = :estado
                WHERE id_usuario  = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'        => $usuario->getNombre(),
            ':apellido'      => $usuario->getApellido(),
            ':email'         => $usuario->getEmail(),
            ':password_hash' => $usuario->getPasswordHash(),
            ':tipo_usuario'  => $usuario->getTipoUsuario(),
            ':estado'        => $usuario->getEstado(),
            ':id'            => $usuario->getIdUsuario()
        ]);
    }

    public function delete(int $id_usuario): bool
    {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id_usuario]);

        return $stmt->rowCount() > 0;
    }
}

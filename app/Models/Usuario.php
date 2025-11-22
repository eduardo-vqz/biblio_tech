<?php

namespace App\Models;

class Usuario
{
    //Propiedades
    private ?int $id_usuario;
    private string $nombre;
    private string $apellido;
    private string $email;
    private string $password_hash;
    private string $tipo_usuario;     // ADMIN | BIBLIOTECARIO | ESTUDIANTE
    private int $estado;              // 1 = activo | 0 = inactivo
    private ?string $fecha_registro;  // DATETIME (yyyy-mm-dd hh:mm:ss)

    //constructor
    public function __construct(
        ?int $id_usuario = null,
        string $nombre = "",
        string $apellido = "",
        string $email = "",
        string $password_hash = "",
        string $tipo_usuario = "ESTUDIANTE",
        int $estado = 1,
        ?string $fecha_registro = null
    ) {
        $this->id_usuario     = $id_usuario;
        $this->nombre         = $nombre;
        $this->apellido       = $apellido;
        $this->email          = $email;
        $this->password_hash  = $password_hash;
        $this->tipo_usuario   = $tipo_usuario;
        $this->estado         = $estado;
        $this->fecha_registro = $fecha_registro;
    }

    //Encapsulamiento
    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getTipoUsuario(): string
    {
        return $this->tipo_usuario;
    }

    public function getEstado(): int
    {
        return $this->estado;
    }

    public function getFechaRegistro(): ?string
    {
        return $this->fecha_registro;
    }


    public function setIdUsuario(?int $id): void
    {
        $this->id_usuario = $id;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPasswordHash(string $hash): void
    {
        $this->password_hash = $hash;
    }

    public function setTipoUsuario(string $tipo): void
    {
        $this->tipo_usuario = $tipo;
    }

    public function setEstado(int $estado): void
    {
        $this->estado = $estado;
    }

    public function setFechaRegistro(?string $fecha): void
    {
        $this->fecha_registro = $fecha;
    }

    // Metodos complementarios
    public function getNombreCompleto(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function esAdmin(): bool
    {
        return $this->tipo_usuario === 'ADMIN';
    }

    public function esBibliotecario(): bool
    {
        return $this->tipo_usuario === 'BIBLIOTECARIO';
    }

    public function esEstudiante(): bool
    {
        return $this->tipo_usuario === 'ESTUDIANTE';
    }
}

<?php

namespace App\Models;

class Autor{
    private ?int $id_autor;
    private string $nombre;
    private string $apellido;
    private ?string $nacionalidad;

    public function __construct(
        ?int $id_autor = null,
        string $nombre = "",
        string $apellido = "",
        ?string $nacionalidad = null
    ){
        $this->id_autor = $id_autor;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->nacionalidad = $nacionalidad;
    }

    public function getIdAutor(): ?int {
        return $this->id_autor;
    }
    public function setIdAutor(?int $id): void {
        $this->id_autor = $id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }
    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }
    public function setApellido(string $apellido): void {
        $this->apellido = $apellido;
    }

    public function getNacionalidad(): ?string {
        return $this->nacionalidad;
    }
    public function setNacionalidad(?string $nacionalidad): void {
        $this->nacionalidad = $nacionalidad;
    }

    public function getNombreCompleto(): string {
        return "{$this->nombre} {$this->apellido}";
    }
}
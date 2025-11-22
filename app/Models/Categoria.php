<?php

namespace App\Models;

class Categorias
{

    private ?int $id_categoria;
    private string $nombre;
    private ?string $descripcion;

    public function __construct(
        ?int $id_categoria = null,
        string $nombre = "",
        ?string $descripcion = null
    ) {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    // Encapsulamiento
    public function getIdCategoria(): ?int
    {
        return $this->id_categoria;
    }
    public function setIdCategoria(?int $id): void
    {
        $this->id_categoria = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

     public function __toString(): string {
        return $this->nombre;
    }
}

<?php

namespace App\Models;

class LibroAutor
{
    private int $id_libro;
    private int $id_autor;

    public function __construct(
        int $id_libro,
        int $id_autor
    ) {
        $this->id_libro = $id_libro;
        $this->id_autor = $id_autor;
    }

    public function getIdLibro(): int
    {
        return $this->id_libro;
    }
    public function setIdLibro(int $id_libro): void
    {
        $this->id_libro = $id_libro;
    }

    public function getIdAutor(): int
    {
        return $this->id_autor;
    }

    public function setIdAutor(int $id_autor): void
    {
        $this->id_autor = $id_autor;
    }
}

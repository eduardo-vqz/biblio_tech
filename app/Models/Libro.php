<?php
namespace App\Models;

class Libro
{
    private ?int $id_libro;
    private string $titulo;
    private string $isbn;
    private int $anio_publicacion;
    private int $id_categoria;
    private ?string $descripcion;
    private int $stock_total;
    private int $stock_disponible;
    private string $estado;
    private ?string $fecha_registro;

    // (Opcional) nombre de categoría si haces JOIN en el SELECT
    private ?string $nombre_categoria = null;

    public function __construct(
        ?int $id_libro = null,
        string $titulo = '',
        string $isbn = '',
        int $anio_publicacion = 0,
        int $id_categoria = 0,
        ?string $descripcion = null,
        int $stock_total = 0,
        int $stock_disponible = 0,
        string $estado = 'DISPONIBLE',
        ?string $fecha_registro = null
    ) {
        $this->id_libro         = $id_libro;
        $this->titulo           = $titulo;
        $this->isbn             = $isbn;
        $this->anio_publicacion = $anio_publicacion;
        $this->id_categoria     = $id_categoria;
        $this->descripcion      = $descripcion;
        $this->stock_total      = $stock_total;
        $this->stock_disponible = $stock_disponible;
        $this->estado           = $estado;
        $this->fecha_registro   = $fecha_registro;
    }

    public function getIdLibro(): ?int
    {
        return $this->id_libro;
    }

    public function setIdLibro(?int $id_libro): void
    {
        $this->id_libro = $id_libro;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getAnioPublicacion(): int
    {
        return $this->anio_publicacion;
    }

    public function setAnioPublicacion(int $anio_publicacion): void
    {
        $this->anio_publicacion = $anio_publicacion;
    }

    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    public function setIdCategoria(int $id_categoria): void
    {
        $this->id_categoria = $id_categoria;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getStockTotal(): int
    {
        return $this->stock_total;
    }

    public function setStockTotal(int $stock_total): void
    {
        $this->stock_total = $stock_total;
    }

    public function getStockDisponible(): int
    {
        return $this->stock_disponible;
    }

    public function setStockDisponible(int $stock_disponible): void
    {
        $this->stock_disponible = $stock_disponible;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getFechaRegistro(): ?string
    {
        return $this->fecha_registro;
    }

    public function setFechaRegistro(?string $fecha_registro): void
    {
        $this->fecha_registro = $fecha_registro;
    }

    // Opcional: para mostrar nombre de categoría cuando viene en un JOIN
    public function getNombreCategoria(): ?string
    {
        return $this->nombre_categoria;
    }

    public function setNombreCategoria(?string $nombre_categoria): void
    {
        $this->nombre_categoria = $nombre_categoria;
    }
}

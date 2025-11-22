<?php

namespace App\Models;

class Libro
{
    //Propiedades del modelo
    private ?int $id_libro;
    private string $titulo;
    private ?string $isbn;
    private ?int $id_categoria;
    private ?Categoria $categoria;
    private int $stock_total;
    private int $stock_disponible;
    private string $estado;
    private ?string $descripcion;
    private ?string $anio_publicacion;
    private ?string $fecha_registro;

    public function __construct(
        ?int $id_libro = null,
        string $titulo = "",
        ?string $isbn = null,
        ?int $id_categoria = null,
        ?Categoria $categoria = null,
        int $stock_total = 1,
        int $stock_disponible = 1,
        string $estado = "DISPONIBLE",
        ?string $descripcion = null,
        ?int $anio_publicacion = null,
        ?string $fecha_registro = null
    ) {
        $this->id_libro         = $id_libro;
        $this->titulo           = $titulo;
        $this->isbn             = $isbn;
        $this->id_categoria     = $id_categoria;
        $this->categoria        = $categoria;
        $this->stock_total      = $stock_total;
        $this->stock_disponible = $stock_disponible;
        $this->estado           = $estado;
        $this->descripcion      = $descripcion;
        $this->anio_publicacion = $anio_publicacion;
        $this->fecha_registro   = $fecha_registro;
    }

    //Encapsulamiento
    public function getIdLibro(): ?int
    {
        return $this->id_libro;
    }

    public function setIdLibro(?int $id): void
    {
        $this->id_libro = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getIdCategoria(): ?int
    {
        return $this->id_categoria;
    }

    public function setIdCategoria(?int $id_categoria): void
    {
        $this->id_categoria = $id_categoria;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }
    public function setCategoria(?Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function getStockTotal(): int
    {
        return $this->stock_total;
    }

    public function setStockTotal(int $stock): void
    {
        $this->stock_total = $stock;
    }

    public function getStockDisponible(): int
    {
        return $this->stock_disponible;
    }

    public function setStockDisponible(int $stock): void
    {
        $this->stock_disponible = $stock;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getAnioPublicacion(): ?int
    {
        return $this->anio_publicacion;
    }

    public function setAnioPublicacion(?int $anio): void
    {
        $this->anio_publicacion = $anio;
    }

    public function getFechaRegistro(): ?string
    {
        return $this->fecha_registro;
    }

    public function setFechaRegistro(?string $fecha): void
    {
        $this->fecha_registro = $fecha;
    }
}

<?php

namespace App\Models;

class Prestamo
{

    private ?int $id_prestamo;
    private int $id_usuario;
    private int $id_libro;
    private string $fecha_prestamo;      // YYYY-MM-DD
    private string $fecha_devolucion;    // YYYY-MM-DD
    private ?string $fecha_devuelto;     // NULL si no ha devuelto
    private string $estado;              // PRESTADO | DEVUELTO | ATRASADO | CANCELADO
    private ?string $observaciones;

    public function __construct(
        ?int $id_prestamo = null,
        int $id_usuario = 0,
        int $id_libro = 0,
        string $fecha_prestamo = "",
        string $fecha_devolucion = "",
        ?string $fecha_devuelto = null,
        string $estado = "PRESTADO",
        ?string $observaciones = null
    ) {
        $this->id_prestamo      = $id_prestamo;
        $this->id_usuario       = $id_usuario;
        $this->id_libro         = $id_libro;
        $this->fecha_prestamo   = $fecha_prestamo;
        $this->fecha_devolucion = $fecha_devolucion;
        $this->fecha_devuelto   = $fecha_devuelto;
        $this->estado           = $estado;
        $this->observaciones    = $observaciones;
    }


    //Encapsulamiento
    public function getIdPrestamo(): ?int
    {
        return $this->id_prestamo;
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    public function getIdLibro(): int
    {
        return $this->id_libro;
    }

    public function getFechaPrestamo(): string
    {
        return $this->fecha_prestamo;
    }

    public function getFechaDevolucion(): string
    {
        return $this->fecha_devolucion;
    }

    public function getFechaDevuelto(): ?string
    {
        return $this->fecha_devuelto;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setIdPrestamo(?int $id): void
    {
        $this->id_prestamo = $id;
    }

    public function setIdUsuario(int $id): void
    {
        $this->id_usuario = $id;
    }

    public function setIdLibro(int $id): void
    {
        $this->id_libro = $id;
    }

    public function setFechaPrestamo(string $fecha): void
    {
        $this->fecha_prestamo = $fecha;
    }

    public function setFechaDevolucion(string $fecha): void
    {
        $this->fecha_devolucion = $fecha;
    }

    public function setFechaDevuelto(?string $fecha): void
    {
        $this->fecha_devuelto = $fecha;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function setObservaciones(?string $obs): void
    {
        $this->observaciones = $obs;
    }


    // ;etpdps auxiliares
    public function estaDevuelto(): bool
    {
        return $this->estado === 'DEVUELTO';
    }

    public function estaAtrasado(): bool
    {
        return $this->estado === 'ATRASADO';
    }

    public function estaPrestado(): bool
    {
        return $this->estado === 'PRESTADO';
    }
}

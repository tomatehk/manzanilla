<?php

namespace MSM\ProductosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registro
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Registro
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=150)
     */
    private $producto;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var decimal
     *
     * @ORM\Column(name="costo", type="decimal", scale=2)
     */
    private $costo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creado", type="date")
     */
    private $creado;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;    

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=15, nullable=true)
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_pago", type="string", length=25, nullable=true)
     */
    private $tipo_pago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualizado", type="date")
     */
    private $actualizado;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set producto
     *
     * @param string $producto
     * @return Registro
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    /**
     * Get producto
     *
     * @return string 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Registro
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set descripcion
     *
     * @param integer $cantidad
     * @return Registro
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set costo
     *
     * @param string $costo
     * @return Registro
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return string 
     */
    public function getCosto()
    {
        return $this->costo;
    }

      public function setCreado($creado)
    {
        $this->creado = $creado;

        return $this;
    }

    /**
     * Get creado
     *
     * @return \DateTime 
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * Set actualizado
     *
     * @param \DateTime $actualizado
     * @return Rmotos
     */
    public function setActualizado($actualizado)
    {
        $this->actualizado = $actualizado;

        return $this;
    }

    /**
     * Get actualizado
     *
     * @return \DateTime 
     */
    public function getActualizado()
    {
        return $this->actualizado;
    }

    /**
    * @ORM\PrePersist
    */
    public function creadoPrePersit()
    {
        $this->creado = new \DateTime();
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function actualizadoPreUpdate()
    {
        $this->actualizado = new \DateTime();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Registro
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return Registro
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set tipo_pago
     *
     * @param string $tipoPago
     * @return Registro
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipo_pago = $tipoPago;

        return $this;
    }

    /**
     * Get tipo_pago
     *
     * @return string 
     */
    public function getTipoPago()
    {
        return $this->tipo_pago;
    }
}

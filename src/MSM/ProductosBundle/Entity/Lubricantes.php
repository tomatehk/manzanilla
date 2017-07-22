<?php

namespace MSM\ProductosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lubricantes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MSM\ProductosBundle\Entity\LubricantesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Lubricantes
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
     * @ORM\Column(name="aceite", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $aceite;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     * @Assert\NotBlank()
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="costo", type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    private $costo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creado", type="date")
     */
    private $creado;

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
     * Set aceite
     *
     * @param string $aceite
     * @return Lubricantes
     */
    public function setAceite($aceite)
    {
        $this->aceite = $aceite;

        return $this;
    }

    /**
     * Get aceite
     *
     * @return string 
     */
    public function getAceite()
    {
        return $this->aceite;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Lubricantes
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Lubricantes
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
     * @return Lubricantes
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

    /**
     * Set creado
     *
     * @param \DateTime $creado
     * @return Rmotos
     */
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

}

<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 12/08/2015
 * Time: 16:54
 */

namespace Booking\BookingBundle\Model;

class ArbolG
{
    private $raiz;
    private $hijos;

    public function __construct()
    {
        $this->raiz = null;
        $this->hijos = array();
    }

    /**
     * Devuelve el elemento que esta en la raiz del arbol o subarbol
     * @return ArbolG $raiz
     */
    public function getRaiz()
    {
        return $this->raiz;
    }

    /**
     * Establece un valor para la raiz del arbol o subarbol
     * @param mixed $pRaiz
     * @return mixed
     */
    public function setRaiz($pRaiz)
    {
        return $this->raiz = $pRaiz;
    }

    /**
     * Devuelve true si esta vacia la lista de hijos
     * @return boolean
     */
    public function esHoja()
    {
        return count($this->hijos)==0;
    }

    /**
     * Adiciona un nuevo hijo al elemento raiz
     * @param ArbolG $pArbol
     */
    public function adicionarSubArbol($pArbol)
    {
        $subArbol = new ArbolG();
        $subArbol->setRaiz($pArbol);
        array_push($this->hijos, $subArbol);
    }

    /**
     * Eliminar el elemento hijo de la raiz especificado
     * @param integer $pPos
     */
    public function Podar($pPos)
    {
        unset($this->hijos[$pPos]);
    }

    /**
     * Devuelve el grado del arbol
     * @return integer
     */
    public function Grado()
    {
        return sizeof($this->hijos);
    }

    /**
     * Obtiene el hijo especificado
     * @param integer $pPos
     * @return ArbolG
     *
     * TODO
     * Si el valor pasado en $pPos es mayor que la cantidad de hijos, genera un error. Debe lanzar una excepcion
     */
    public function subArbol($pPos)
    {
        return $this->hijos[$pPos];
    }
}

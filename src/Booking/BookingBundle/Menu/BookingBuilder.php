<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 21/06/15
 * Time: 0:13
 */

namespace Booking\BookingBundle\Menu;


use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\FactoryInterface;

class BookingBuilder extends ContainerAware{

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'collapse');
        $menu->setChildrenAttribute('id', 'menu');

        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $menu->addChild('Administración', array('route' => 'homepage'))->setAttribute('icon','icon-table');

        $menu->addChild('Gestión de Casas', array(
            'route' => 'casa',

        ))->setAttribute('icon', 'icon-home');

        $menu->addChild('Gestión de Clientes', array(
            'route' => 'cliente',

        ))->setAttribute('icon', 'icon-group');

        $menu->addChild('Gestión de Habitación', array(
            'route' => 'habitacion',

        ))->setAttribute('icon', 'icon-suitcase');

        $menu->addChild('Gestión de Reservaciones', array(
            'route' => 'reservacion_index',

        ))->setAttribute('icon', 'icon-book');

        $menu->addChild('Gestión de Propietario', array(
            'route' => 'propietario',

        ))->setAttribute('icon', 'icon-lock');

        $menu->addChild('', array(


        ))->setAttribute('class', 'divider');

        $menu->addChild('Gestión de Agencia', array(
            'route' => 'agencia',

        ))->setAttribute('icon', 'icon-globe');

        $menu->addChild('Gestión de Tipo de Actividad', array(
            'route' => 'tipoactividad',

        ))->setAttribute('icon', 'icon-gamepad');

        $menu->addChild('Gestión de Tipo de Habitación', array(
            'route' => 'tipohab',

        ))->setAttribute('icon', 'icon-star');




        return $menu;
    }
} 
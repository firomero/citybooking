<?php
/**
 * Created by PhpStorm.
 * User: firomero
 * Date: 8/31/2015
 * Time: 11:10 AM
 */

namespace Booking\BookingBundle\Menu\Renderer;


use Desarrolla2\Cache\Adapter\AbstractAdapter;
use Desarrolla2\Cache\Cache;
use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer;


class ApcRenderer extends ListRenderer {


   protected $cache;
   const APC_MENU = 'APC_MENU';
    public function __construct($charset = null, AbstractAdapter $adapter, $ttl = 3600){

        parent::__construct(array(),$charset);
        $adapter->setOption('ttl',$ttl);
        $this->cache =new Cache($adapter);

    }

    /**
     * Renders a menu with the specified renderer.
     *
     * @param \Knp\Menu\ItemInterface $item
     * @param array $options
     * @return string
     */
    public function render(ItemInterface $item, array $options = array())
    {
        $html='';
        if ($html = $this->cache->get(self::APC_MENU)) {
             return $html;
        }
        else{
            $html = parent::render($item,$options);
            $this->cache->set(self::APC_MENU,$html);
        }

        return $html;
    }



}
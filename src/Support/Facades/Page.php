<?php

namespace CubeSystems\Leaf\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Page extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'leaf_page_builder';
    }
}
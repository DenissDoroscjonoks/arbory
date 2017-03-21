<?php

namespace CubeSystems\Leaf\Nodes;

use Closure;
use CubeSystems\Leaf\Exceptions\BadMethodCallException;
use Illuminate\Support\Collection;

/**
 * Class Router
 * @package CubeSystems\Leaf\Nodes\Routing
 */
class ContentTypeRoutesRegister
{
    /**
     * @var array|Closure[]
     */
    protected $contentTypeHandlers = [];

    /**
     * @var ContentTypeRegister
     */
    protected $contentTypesRegister;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->contentTypesRegister = app()->make( ContentTypeRegister::class );
    }

    /**
     * @param $contentType
     * @param Closure $handler
     * @return $this
     * @throws BadMethodCallException
     */
    public function register( $contentType, Closure $handler )
    {
        if( !$this->contentTypesRegister->isValidContentType( $contentType ) )
        {
            throw new BadMethodCallException( 'Invalid content type' );
        }

        $this->contentTypeHandlers[$contentType] = $handler;

        return $this;
    }

    /**
     * @param $contentType
     * @return Closure|null
     */
    public function getContentTypeHandler( $contentType )
    {
        if( !array_key_exists( $contentType, $this->contentTypeHandlers ) )
        {
            return null;
        }

        return $this->contentTypeHandlers[$contentType];
    }

    /**
     * @return \Illuminate\Routing\Router
     */
    public function getRouter()
    {
        return app( 'router' );
    }

    /**
     * @return Node|null
     */
    public function getCurrentNode()
    {
        $currentRouteName = $this->getRouter()->getCurrentRoute()->getName();

        if( !preg_match( '#^node\.(?P<id>\d+)\.#', $currentRouteName, $matches ) )
        {
            return null;
        }

        return Node::with( 'content' )->find( $matches['id'] );
    }

    public function registerNodes()
    {
        $this->registerRoutesForNodeCollection( Node::all()->unorderedHierarchicalList() );
    }

    /**
     * @param Collection|Node[] $items
     * @param string $base
     */
    protected function registerRoutesForNodeCollection( Collection $items, $base = '' )
    {
        foreach( $items as $item )
        {
            $slug = $base . '/' . $item->getSlug();

            $this->registerNodeRoutes( $item, $slug );

            if( $item->children->count() )
            {
                $this->registerRoutesForNodeCollection( $item->children, $slug );
            }
        }
    }

    /**
     * @param Node $node
     * @param $slug
     */
    protected function registerNodeRoutes( Node $node, $slug )
    {
        $attributes = [
            'as' => 'node.' . $node->getKey() . '.',
            'prefix' => $slug,
            'namespace' => false,
        ];

        $this->getRouter()->group( $attributes, $this->getContentTypeHandler( $node->getContentType() ) );
    }
}

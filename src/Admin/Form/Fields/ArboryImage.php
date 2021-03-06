<?php declare( strict_types=1 );

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Renderer\ImageFieldRenderer;
use Arbory\Base\Html\Elements\Element;

/**
 * Class ArboryImage
 * @package Arbory\Base\Admin\Form\Fields
 */
final class ArboryImage extends ArboryFile
{
    /**
     * @return Element
     */
    public function render(): Element
    {
        return ( new ImageFieldRenderer( $this ) )->render();
    }
}

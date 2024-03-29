<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Barcode\Renderer;

use DOMDocument;
use DOMElement;
use DOMText;

/**
 * Class for rendering the barcode as svg
 */
class Svg extends AbstractRenderer
{
    /**
     * Resource for the image
     * @var DOMDocument
     */
    protected $resource = null;

    /**
     * Root element of the XML structure
     * @var DOMElement
     */
    protected $rootElement = null;

    /**
     * Height of the rendered image wanted by user
     * @var int
     */
    protected $userHeight = 0;

    /**
     * Width of the rendered image wanted by user
     * @var int
     */
    protected $userWidth = 0;

    /**
     * Flag to determime if drawPolygon has been run once already
     * @var bool
     */
    protected $drawPolygonExecuted = false;

    /**
     * Set height of the result image
     * @param null|int $value
     * @throws Exception\OutOfRangeException
     * @return Svg
     */
    public function setHeight($value)
    {
        if (!is_numeric($value) || intval($value) < 0) {
            throw new Exception\OutOfRangeException(
                'Svg height must be greater than or equals 0'
            );
        }
        $this->userHeight = intval($value);
        return $this;
    }

    /**
     * Get barcode height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->userHeight;
    }

    /**
     * Set barcode width
     *
     * @param mixed $value
     * @throws Exception\OutOfRangeException
     * @return self
     */
    public function setWidth($value)
    {
        if (!is_numeric($value) || intval($value) < 0) {
            throw new Exception\OutOfRangeException(
                'Svg width must be greater than or equals 0'
            );
        }
        $this->userWidth = intval($value);
        return $this;
    }

    /**
     * Get barcode width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->userWidth;
    }

    /**
     * Set an image resource to draw the barcode inside
     *
     * @param  DOMDocument $svg
     * @return Svg
     */
    public function setResource(DOMDocument $svg)
    {
        $this->resource = $svg;
        return $this;
    }

    /**
     * Initialize the image resource
     *
     * @return void
     */
    protected function initRenderer()
    {
        $barcodeWidth  = $this->barcode->getWidth(true);
        $barcodeHeight = $this->barcode->getHeight(true);

        $backgroundColor = $this->barcode->getBackgroundColor();
        $imageBackgroundColor = 'rgb(' . implode(', ', [($backgroundColor & 0xFF0000) >> 16,
                                                             ($backgroundColor & 0x00FF00) >> 8,
                                                             ($backgroundColor & 0x0000FF)]) . ')';

        $width = $barcodeWidth;
        $height = $barcodeHeight;
        if ($this->userWidth && $this->barcode->getType() != 'error') {
            $width = $this->userWidth;
        }
        if ($this->userHeight && $this->barcode->getType() != 'error') {
            $height = $this->userHeight;
        }
        if ($this->resource === null) {
            $this->resource = new DOMDocument('1.0', 'utf-8');
            $this->resource->formatOutput = true;
            $this->rootElement = $this->resource->createElement('svg');
            $this->rootElement->setAttribute('xmlns', "http://www.w3.org/2000/svg");
            $this->rootElement->setAttribute('version', '1.1');
            $this->rootElement->setAttribute('width', $width);
            $this->rootElement->setAttribute('height', $height);

            $this->appendRootElement(
                'title',
                [],
                "Barcode " . strtoupper($this->barcode->getType()) . " " . $this->barcode->getText()
            );
        } else {
            $this->readRootElement();
            $width = $this->rootElement->getAttribute('width');
            $height = $this->rootElement->getAttribute('height');
        }
        $this->adjustPosition($height, $width);

        $rect = ['x' => $this->leftOffset,
            'y' => $this->topOffset,
            'width' => ($this->leftOffset + $barcodeWidth - 1),
            'height' => ($this->topOffset + $barcodeHeight - 1),
            'fill' => $imageBackgroundColor];

        if ($this->transparentBackground) {
            $rect['fill-opacity'] = 0;
        }

        $this->appendRootElement('rect', $rect);
    }

    protected function readRootElement()
    {
        if ($this->resource !== null) {
            $this->rootElement = $this->resource->documentElement;
        }
    }

    /**
     * Append a new DOMElement to the root element
     *
     * @param string $tagName
     * @param array $attributes
     * @param string $textContent
     */
    protected function appendRootElement($tagName, $attributes = [], $textContent = null)
    {
        $newElement = $this->createElement($tagName, $attributes, $textContent);
        $this->rootElement->appendChild($newElement);
    }

    /**
     * Create DOMElement
     *
     * @param string $tagName
     * @param array $attributes
     * @param string $textContent
     * @return DOMElement
     */
    protected function createElement($tagName, $attributes = [], $textContent = null)
    {
        $element = $this->resource->createElement($tagName);
        foreach ($attributes as $k => $v) {
            $element->setAttribute($k, $v);
        }
        if ($textContent !== null) {
            $element->appendChild(new DOMText((string) $textContent));
        }
        return $element;
    }

    /**
     * Check barcode parameters
     *
     * @return void
     */
    protected function checkSpecificParams()
    {
        $this->checkDimensions();
    }

    /**
     * Check barcode dimensions
     *
     * @throws Exception\RuntimeException
     * @return void
     */
    protected function checkDimensions()
    {
        if ($this->resource !== null) {
            $this->readRootElement();
            $height = (float) $this->rootElement->getAttribute('height');
            if ($height < $this->barcode->getHeight(true)) {
                throw new Exception\RuntimeException(
                    'Barcode is define outside the image (height)'
                );
            }
        } else {
            if ($this->userHeight) {
                $height = $this->barcode->getHeight(true);
                if ($this->userHeight < $height) {
                    throw new Exception\RuntimeException(sprintf(
                        "Barcode is define outside the image (calculated: '%d', provided: '%d')",
                        $height,
                        $this->userHeight
                    ));
                }
            }
        }
        if ($this->resource !== null) {
            $this->readRootElement();
            $width = $this->rootElement->getAttribute('width');
            if ($width < $this->barcode->getWidth(true)) {
                throw new Exception\RuntimeException(
                    'Barcode is define outside the image (width)'
                );
            }
        } else {
            if ($this->userWidth) {
                $width = (float) $this->barcode->getWidth(true);
                if ($this->userWidth < $width) {
                    throw new Exception\RuntimeException(sprintf(
                        "Barcode is define outside the image (calculated: '%d', provided: '%d')",
                        $width,
                        $this->userWidth
                    ));
                }
            }
        }
    }

    /**
     * Draw the barcode in the rendering resource
     * @return DOMDocument
     */
    public function draw()
    {
        parent::draw();
        $this->resource->appendChild($this->rootElement);
        return $this->resource;
    }

    /**
     * Draw and render the barcode with correct headers
     *
     * @return mixed
     */
    public function render()
    {
        $this->draw();
        header("Content-Type: image/svg+xml");
        echo $this->resource->saveXML();
    }

    /**
     * Draw a polygon in the svg resource
     *
     * @param array $points
     * @param int $color
     * @param  bool $filled
     */
    protected function drawPolygon($points, $color, $filled = true)
    {
        $color = 'rgb(' . implode(', ', [($color & 0xFF0000) >> 16,
                                              ($color & 0x00FF00) >> 8,
                                              ($color & 0x0000FF)]) . ')';
        $orientation = $this->getBarcode()->getOrientation();
        $newPoints = [
            $points[0][0] + $this->leftOffset,
            $points[0][1] + $this->topOffset,
            $points[1][0] + $this->leftOffset,
            $points[1][1] + $this->topOffset,
            $points[2][0] + $this->leftOffset + cos(-$orientation),
            $points[2][1] + $this->topOffset - sin($orientation),
            $points[3][0] + $this->leftOffset + cos(-$orientation),
            $points[3][1] + $this->topOffset - sin($orientation),
        ];
        $newPoints = implode(' ', $newPoints);
        $attributes = [];
        $attributes['points'] = $newPoints;
        $attributes['fill'] = $color;

        // SVG passes a rect in as the first call to drawPolygon, we'll need to intercept
        // this and set transparency if necessary.
        if (!$this->drawPolygonExecuted) {
            if ($this->transparentBackground) {
                $attributes['fill-opacity'] = '0';
            }
            $this->drawPolygonExecuted = true;
        }

        $this->appendRootElement('polygon', $attributes);
    }

    /**
     * Draw a polygon in the svg resource
     *
     * @param string $text
     * @param float $size
     * @param array $position
     * @param string $font
     * @param int $color
     * @param string $alignment
     * @param float $orientation
     */
    protected function drawText($text, $size, $position, $font, $color, $alignment = 'center', $orientation = 0)
    {
        $color = 'rgb(' . implode(', ', [($color & 0xFF0000) >> 16,
                                              ($color & 0x00FF00) >> 8,
                                              ($color & 0x0000FF)]) . ')';
        $attributes = [];
        $attributes['x'] = $position[0] + $this->leftOffset;
        $attributes['y'] = $position[1] + $this->topOffset;
        //$attributes['font-family'] = $font;
        $attributes['color'] = $color;
        $attributes['font-size'] = $size * 1.2;
        switch ($alignment) {
            case 'left':
                $textAnchor = 'start';
                break;
            case 'right':
                $textAnchor = 'end';
                break;
            case 'center':
            default:
                $textAnchor = 'middle';
        }
        $attributes['style'] = 'text-anchor: ' . $textAnchor;
        $attributes['transform'] = 'rotate('
                                 . (- $orientation)
                                 . ', '
                                 . ($position[0] + $this->leftOffset)
                                 . ', ' . ($position[1] + $this->topOffset)
                                 . ')';
        $this->appendRootElement('text', $attributes, $text);
    }
}

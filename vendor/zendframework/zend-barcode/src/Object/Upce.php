<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Barcode\Object;

use Zend\Validator\Barcode as BarcodeValidator;

/**
 * Class for generate UpcA barcode
 */
class Upce extends Ean13
{
    protected $parities = [
        0 => [
            0 => ['B','B','B','A','A','A'],
            1 => ['B','B','A','B','A','A'],
            2 => ['B','B','A','A','B','A'],
            3 => ['B','B','A','A','A','B'],
            4 => ['B','A','B','B','A','A'],
            5 => ['B','A','A','B','B','A'],
            6 => ['B','A','A','A','B','B'],
            7 => ['B','A','B','A','B','A'],
            8 => ['B','A','B','A','A','B'],
            9 => ['B','A','A','B','A','B']],
        1 => [
            0 => ['A','A','A','B','B','B'],
            1 => ['A','A','B','A','B','B'],
            2 => ['A','A','B','B','A','B'],
            3 => ['A','A','B','B','B','A'],
            4 => ['A','B','A','A','B','B'],
            5 => ['A','B','B','A','A','B'],
            6 => ['A','B','B','B','A','A'],
            7 => ['A','B','A','B','A','B'],
            8 => ['A','B','A','B','B','A'],
            9 => ['A','B','B','A','B','A']]
    ];

    /**
     * Default options for Postnet barcode
     * @return void
     */
    protected function getDefaultOptions()
    {
        $this->barcodeLength = 8;
        $this->mandatoryChecksum = true;
        $this->mandatoryQuietZones = true;
    }

    /**
     * Retrieve text to encode
     * @return string
     */
    public function getText()
    {
        $text = parent::getText();
        if ($text[0] != 1) {
            $text[0] = 0;
        }
        return $text;
    }

    /**
     * Width of the barcode (in pixels)
     * @return int
     */
    protected function calculateBarcodeWidth()
    {
        $quietZone       = $this->getQuietZone();
        $startCharacter  = (3 * $this->barThinWidth) * $this->factor;
        $stopCharacter   = (6 * $this->barThinWidth) * $this->factor;
        $encodedData     = (7 * $this->barThinWidth) * $this->factor * 6;
        return $quietZone + $startCharacter + $encodedData + $stopCharacter + $quietZone;
    }

    /**
     * Prepare array to draw barcode
     * @return array
     */
    protected function prepareBarcode()
    {
        $barcodeTable = [];
        $height = ($this->drawText) ? 1.1 : 1;

        // Start character (101)
        $barcodeTable[] = [1, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [0, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [1, $this->barThinWidth, 0, $height];

        $textTable = str_split($this->getText());
        $system = 0;
        if ($textTable[0] == 1) {
            $system = 1;
        }
        $checksum = $textTable[7];
        $parity = $this->parities[$system][$checksum];

        for ($i = 1; $i < 7; $i++) {
            $bars = str_split($this->codingMap[$parity[$i - 1]][$textTable[$i]]);
            foreach ($bars as $b) {
                $barcodeTable[] = [$b, $this->barThinWidth, 0, 1];
            }
        }

        // Stop character (10101)
        $barcodeTable[] = [0, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [1, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [0, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [1, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [0, $this->barThinWidth, 0, $height];
        $barcodeTable[] = [1, $this->barThinWidth, 0, $height];
        return $barcodeTable;
    }

    /**
     * Partial function to draw text
     * @return void
     */
    protected function drawText()
    {
        if ($this->drawText) {
            $text = $this->getTextToDisplay();
            $characterWidth = (7 * $this->barThinWidth) * $this->factor;
            $leftPosition = $this->getQuietZone() - $characterWidth;
            for ($i = 0; $i < $this->barcodeLength; $i ++) {
                $fontSize = $this->fontSize;
                if ($i == 0 || $i == 7) {
                    $fontSize *= 0.8;
                }
                $this->addText(
                    $text{$i},
                    $fontSize * $this->factor,
                    $this->rotate(
                        $leftPosition,
                        (int) $this->withBorder * 2 + $this->factor * ($this->barHeight + $fontSize) + 1
                    ),
                    $this->font,
                    $this->foreColor,
                    'left',
                    - $this->orientation
                );
                switch ($i) {
                    case 0:
                        $factor = 3;
                        break;
                    case 6:
                        $factor = 5;
                        break;
                    default:
                        $factor = 0;
                }
                $leftPosition = $leftPosition + $characterWidth + ($factor * $this->barThinWidth * $this->factor);
            }
        }
    }

    /**
     * Particular validation for Upce barcode objects
     * (to suppress checksum character substitution)
     *
     * @param string $value
     * @param array  $options
     * @throws Exception\BarcodeValidationException
     */
    protected function validateSpecificText($value, $options = [])
    {
        $validator = new BarcodeValidator([
            'adapter'  => 'upce',
            'checksum' => false,
        ]);

        $value = $this->addLeadingZeros($value, true);

        if (!$validator->isValid($value)) {
            $message = implode("\n", $validator->getMessages());
            throw new Exception\BarcodeValidationException($message);
        }
    }

    /**
     * Get barcode checksum
     *
     * @param  string $text
     * @return int
     */
    public function getChecksum($text)
    {
        $text = $this->addLeadingZeros($text, true);
        if ($text[0] != 1) {
            $text[0] = 0;
        }
        return parent::getChecksum($text);
    }
}

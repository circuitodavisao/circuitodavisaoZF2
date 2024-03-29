<?php
/*
 * This file is part of PHP Copy/Paste Detector (PHPCPD).
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\PHPCPD\Detector;

use SebastianBergmann\PHPCPD\Detector\Strategy\AbstractStrategy;
use SebastianBergmann\PHPCPD\CodeCloneMap;
use Symfony\Component\Console\Helper\ProgressHelper;

/**
 * PHPCPD code analyser.
 *
 * @author    Johann-Peter Hartmann <johann-peter.hartmann@mayflower.de>
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/phpcpd/tree
 * @since     Class available since Release 1.0.0
 */
class Detector
{
    /**
     * @var SebastianBergmann\PHPCPD\Detector\Strategy\AbstractStrategy
     */
    protected $strategy;

    /**
     * @var Symfony\Component\Console\Helper\ProgressHelper
     */
    protected $progressHelper;

    /**
     * Constructor.
     *
     * @param AbstractStrategy $strategy
     * @since Method available since Release 1.3.0
     */
    public function __construct(AbstractStrategy $strategy, ProgressHelper $progressHelper = null)
    {
        $this->strategy       = $strategy;
        $this->progressHelper = $progressHelper;
    }

    /**
     * Copy & Paste Detection (CPD).
     *
     * @param  Iterator|array $files     List of files to process
     * @param  integer        $minLines  Minimum number of identical lines
     * @param  integer        $minTokens Minimum number of identical tokens
     * @param  boolean        $fuzzy
     * @return CodeCloneMap   Map of exact clones found in the list of files
     */
    public function copyPasteDetection($files, $minLines = 5, $minTokens = 70, $fuzzy = false)
    {
        $result = new CodeCloneMap;

        foreach ($files as $file) {
            $this->strategy->processFile(
                $file,
                $minLines,
                $minTokens,
                $result,
                $fuzzy
            );

            if ($this->progressHelper !== null) {
                $this->progressHelper->advance();
            }
        }

        return $result;
    }
}

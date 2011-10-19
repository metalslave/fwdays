<?php

/*
 * This file is part of Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Bundle\DefaultBundle\Twig\Extension;

//use Symfony\Component\HttpKernel\KernelInterface;
//use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;

class IntlExtension extends \Twig_Extension {
    
    public function __construct()
    {
        if (!class_exists('IntlDateFormatter')) {
            throw new RuntimeException('The intl extension is needed to use intl-based filters.');
        }
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'localizeddate' => new \Twig_Filter_Method($this, 'filter'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'intl';
    }

    public function filter($date, $dateFormat = 'medium', $timeFormat = 'medium', $locale = null)
    {
        $formatValues = array(
            'none'   => \IntlDateFormatter::NONE,
            'short'  => \IntlDateFormatter::SHORT,
            'medium' => \IntlDateFormatter::MEDIUM,
            'long'   => \IntlDateFormatter::LONG,
            'full'   => \IntlDateFormatter::FULL,
        );

//        var_dump(\IntlDateFormatter::LONG);
//        exit;
        $formatter = \IntlDateFormatter::create(
            $locale !== null ? $locale : \Locale::getDefault(),
            $formatValues[$dateFormat],
            $formatValues[$timeFormat]
        );

        if (!$date instanceof \DateTime) {
            if (ctype_digit((string) $date)) {
                $date = new \DateTime('@'.$date);
                $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            } else {
                $date = new \DateTime($date);
            }
        }

        return $formatter->format($date->getTimestamp());
    }    
}
<?php

namespace LoPati\NewsletterBundle\Enum;

/**
 * NewsletterTypeEnum class
 *
 * @category Enum
 * @package  LoPati\NewsletterBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterTypeEnum
{
    const NEWS            = 0;
    const EVENTS          = 1;
    const EXPOSITIONS     = 2;
    const RECOMMENDATIONS = 3;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::NEWS             => 'notícies',
            self::EVENTS           => 'activitats',
            self::EXPOSITIONS      => 'exposicions',
            self::RECOMMENDATIONS  => 'recomanem',
        );
    }
}

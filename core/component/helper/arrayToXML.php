<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 23.03.18
 * Time: 19:38
 */

namespace core\component\helper;


trait arrayToXML
{
    public static function toXML(array $array, $root ) {
        $xml = new \SimpleXMLElement( '<' . $root . '/>' );
        foreach( $array as $element=>$value ) {
            $element = is_numeric( $element ) ? $root : $element;
            if (\is_array( $value ) ) {
                if ( self::isNumericKeys( $value ) ) {
                    self::arrayToXML( $value, $xml, $element );
                } else {
                    $$element = $xml->addChild( $element );
                    self::arrayToXML( $value, $$element, $element );
                }
            } else {
                $xml->addChild( $element, $value );
            }
        }
        return $xml->asXML();
    }

    private static function arrayToXML(array $array, &$xml, $root ) {
        foreach( $array as $element=>$value ) {
            $element = is_numeric( $element ) ? $root : $element;
            if (\is_array( $value ) ) {
                if ( self::isNumericKeys( $value ) ) {
                    self::arrayToXML( $value, $xml, $element );
                } else {
                    $$element = $xml->addChild( $element );
                    self::arrayToXML( $value, $$element, $element );
                }
            } else {
                if ( preg_match( '/^@/', $element) ) {
                    $xml->addAttribute( str_replace( '@', '', $element ), $value );
                } else {
                    $xml->addChild( $element, $value );
                }
            }
        }
    }

    private static function isNumericKeys(array $array ) {
        foreach( $array as $key=>$value ) {
            if ( ! is_numeric( $key ) ) {
                return false;
            }
        }
        return true;
    }

}
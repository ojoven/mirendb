<?php

Class Replacer {

    // Code taken from http://github.com/interconnectit/search-replace-db

    // This is just an alias
    public function replace($from = '', $to = '', $data = '', $serialised = false) {

        $data = $this->recursive_unserialize_replace($from, $to, $data, $serialised);
        return $data;

    }

    public function recursive_unserialize_replace( $from = '', $to = '', $data = '', $serialised = false ) {

        try {

            if ( is_string( $data ) && ( $unserialized = @unserialize( $data ) ) !== false ) {
                $data = $this->recursive_unserialize_replace( $from, $to, $unserialized, true );
            }

            elseif ( is_array( $data ) ) {
                $_tmp = array( );
                foreach ( $data as $key => $value ) {
                    $_tmp[ $key ] = $this->recursive_unserialize_replace( $from, $to, $value, false );
                }

                $data = $_tmp;
                unset( $_tmp );
            }

            elseif ( is_object( $data ) ) {
                $_tmp = $data;
                $props = get_object_vars( $data );
                foreach ( $props as $key => $value ) {
                    $_tmp->$key = $this->recursive_unserialize_replace( $from, $to, $value, false );
                }

                $data = $_tmp;
                unset( $_tmp );
            }

            else {
                if ( is_string( $data ) ) {
                    $data = $this->str_replace( $from, $to, $data );
                }
            }

            if ( $serialised )
                return serialize( $data );

        } catch( Exception $error ) {

            // Log error

        }

        return $data;
    }

    public function str_replace( $search, $replace, $string, &$count = 0 ) {
        if (function_exists( 'mb_split' )) {
            return self::mb_str_replace( $search, $replace, $string, $count );
        } else {
            return str_replace( $search, $replace, $string, $count );
        }
    }

    public static function mb_str_replace( $search, $replace, $subject, &$count = 0 ) {
        if ( ! is_array( $subject ) ) {
            // Normalize $search and $replace so they are both arrays of the same length
            $searches = is_array( $search ) ? array_values( $search ) : array( $search );
            $replacements = is_array( $replace ) ? array_values( $replace ) : array( $replace );
            $replacements = array_pad( $replacements, count( $searches ), '' );

            foreach ( $searches as $key => $search ) {
                $parts = mb_split( preg_quote( $search ), $subject );
                $count += count( $parts ) - 1;
                $subject = implode( $replacements[ $key ], $parts );
            }
        } else {
            // Call mb_str_replace for each subject in array, recursively
            foreach ( $subject as $key => $value ) {
                $subject[ $key ] = self::mb_str_replace( $search, $replace, $value, $count );
            }
        }

        return $subject;
    }

}

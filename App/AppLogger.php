<?php

trait AppLogger {

    /** AUXILIAR **/
    public function log($message) {

        if ($this->config['global']['log']) {
            echo $message . PHP_EOL;
        }

    }

}
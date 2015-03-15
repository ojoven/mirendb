<?php

trait AppImporter {

    public function import() {

        /** Initial connections / configurations, they will depend on the behaviour **/
        $behaviour = BehaviourFactory::getBehaviour($this->config['global']['behaviour']);
        $behaviour->import($this);


    }

}
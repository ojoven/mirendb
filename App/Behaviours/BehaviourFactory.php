<?php

class BehaviourFactory {

    public static function getBehaviour($name) {

        switch ($name) {
            case 'standard_control_version':
                // Yes, control version, there we go!
                $behaviour = new StandardControlVersionBehaviour();
                break;
            case 'both_database':
                // If we have 2 databases to compare
                $behaviour = new BothDatabaseBehaviour();
                break;
            case 'both_file':
                // If we have 2 sql dump files to compare
                $behaviour = new BothFileBehaviour();
                break;
            default:
                throw new Exception("You must select a valid behaviour");
        }

        return $behaviour;

    }

}
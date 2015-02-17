<?php

class BehaviourFactory {

    public static function getBehaviour($name) {

        switch ($name) {
            case 'dbvControlVersion':
                // Yes, control version, there we go!
                $behaviour = new DbvControlVersionBehaviour();
                break;
            case 'bothDatabase':
                // If we have 2 databases to compare
                $behaviour = new BothDatabaseBehaviour();
                break;
            case 'bothFile':
                // If we have 2 sql dump files to compare
                $behaviour = new BothFileBehaviour();
                break;
            default:
                throw new Exception("You must select a valid behaviour");
        }

        return $behaviour;

    }

}
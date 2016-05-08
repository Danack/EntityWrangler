<?php


namespace EntityWranglerTest\Hydrator;

class HydratorRegistry
{
    /** @var Hydrator[] */
    private $hydrators = [];
    
    public function __construct()
    {
        $this->hydrators['EntityWranglerTest\Model\EmailAddress'] = new \EntityWranglerTest\Hydrator\EmailAddressHydrator();
        $this->hydrators['EntityWranglerTest\Model\Issue'] = new \EntityWranglerTest\Hydrator\IssueHydrator();
        $this->hydrators['EntityWranglerTest\Model\IssueComment'] = new \EntityWranglerTest\Hydrator\IssueCommentHydrator();
        $this->hydrators['EntityWranglerTest\Model\User'] = new \EntityWranglerTest\Hydrator\UserHydrator();
        
        
        //$this->hydrators['EntityWranglerTest\ModelComposite\IssueWithCommentsAndUserHydrator'] = new \EntityWranglerTest\Hydrator\IssueWithCommentsAndUserHydrator();
        
        
    }

    public function extractValue(array $data, $keyName, $optional = false)
    {
        if (array_key_exists($keyName, $data) === true) {
            return $data[$keyName];
        }
        if ($optional === true) {
            return null;
        }

        throw new HydratorException("Missing key '$keyName' in data ".var_export($data, true));
    }

//    public function extractValueByPath(array $data, array $keyPath, $optional = false)
//    {
//        $loopData = $data;
//        foreach ($keyPath as $keyName) {
//            if (array_key_exists($keyName, $loopData) === false) {
//                if ($optional === true) {
//                    return null;
//                }
//                else {
//                    throw new HydratorException(
//                        "Missing key '$keyName' in data ".var_export($data, true)
//                    );
//                }
//            }
//            $loopData = $loopData[$keyName];
//        }
//        return $loopData;
//    }
    
    public function hydrate($classname, $data, $aliasPrefix)
    {
        if (array_key_exists($classname, $this->hydrators) === false) {
            throw new HydratorException("Cannot instantiate - unregistered class $classname");
        }
        $hydrator = $this->hydrators[$classname];
        //Alias prefix could be stripped here.
        $instance = $hydrator->hydrate($data, $this, $aliasPrefix);
        return $instance;
    }
}

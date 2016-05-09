<?php

namespace EntityWranglerTest\TableGateway;

class IssueCommentTableGateway
{
    private $data;
    private $prefix;
    
    public static function fromResultSet(array $data, $prefix)
    {
        $instance = new self();
        $instance->data = $data;
        $instance->prefix = $prefix;
        
        return $instance;
    }
}

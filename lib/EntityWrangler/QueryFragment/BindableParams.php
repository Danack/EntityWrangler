<?php


namespace EntityWrangler\QueryFragment;


interface BindableParams {

    function &getValue();

    function getType();
}

 
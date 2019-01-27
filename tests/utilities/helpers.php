<?php

function make(string $class, array $params = [], int $count = null)
{
    return factory($class, $count)->make($params);
}

function
create(string $class, array $params = [], int $count = null)
{
    return factory($class, $count)->create($params);
}
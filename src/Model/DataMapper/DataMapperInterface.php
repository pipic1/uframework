<?php

namespace Model\DataMapper;

interface DataMapperInterface
{
    public function persist($object);
    
    public function remove($object);
}

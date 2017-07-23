<?php

namespace Model\Writer;

interface WriterInterface
{
    public function insert($status);

    public function remove($id);
}

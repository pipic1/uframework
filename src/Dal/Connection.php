<?php

namespace Dal;

use \PDO;

class Connection extends PDO
{
    /**
     * @param string $query
     * @param array  $parameters
     *
     * @return bool Returns `true` on success, `false` otherwise
     */
    public function executeQuery($query, array $parameters = [])
    {
        $stmt = $this->prepare($query);
        return $stmt->execute($parameters);
    }
}

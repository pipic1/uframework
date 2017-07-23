<?php

namespace Model\Finder;

use Exception\HttpException;

class JsonFinder implements FinderInterface
{
    private $file = '../data/statuses.json';

    /**
     * Returns all elements.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->readJsonFile();
    }

    /**
     * Retrieve an element by its id.
     *
     * @param mixed $id
     *
     * @return null|mixed
     */
    public function findOneById($id)
    {
        $statuses = $this->readJsonFile();

        foreach ($statuses as $status) {
            if ($status['id'] == $id) {
                return $status;
            }
        }
        throw new HttpException(404, 'Status not found');
    }

    public function addOne($username, $message)
    {
        $statuses = $this->readJsonFile();
        $counter = count($statuses);

        $status['id'] = ++$counter;
        $status['user'] = $username;
        $status['content'] = $message;
        $statuses[] = $status;

        file_put_contents($this->file, json_encode($statuses));
    }

    public function deleteOneById($id)
    {
        $found = false;
        $statuses = $this->readJsonFile();

        foreach ($statuses as $index => $status) {
            if ($status['id'] == $id) {
                $found = true;
                unset($statuses[$index]);
            }
        }

        if ($found == false) {
            throw new HttpException(404, "Status doesn't exist");
        }

        file_put_contents($this->file, json_encode($statuses));
    }

    public function readJsonFile()
    {
        $jsonDataDecode = json_decode(file_get_contents($this->file), true);

        if ($jsonDataDecode === false) {
            //todo EXCEPTION
            echo 'NOON';
            exit();
        }

        return $jsonDataDecode;
    }

    public function saveJsonFile()
    {
    }
}

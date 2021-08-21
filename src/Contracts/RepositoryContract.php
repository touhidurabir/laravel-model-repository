<?php

namespace Touhidurabir\ModelRepository\Contracts;

interface RepositoryContract {
    
    /**
     * Find specific record
     *
     * @param  mixed  $param
     * @param  array  $withs
     * @param  bool   $allowException
     *
     * @return mixed
     */
    public function find($param, array $withs, bool $allowException = true);
    
    
    /**
     * Return all records
     *
     * @return mixed
     */
    public function all();


    /**
     * Create a new record
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data);


    /**
     * Update an existing record
     *
     * @param  array    $data
     * @param  mixed    $prinamryKeyValue
     *
     * @return mixed
     */
    public function update(array $data, $prinamryKeyValue);


    /**
     * Delete record
     *
     * @param  mixed $param
     * @return mixed
     */
    public function delete($param);
    
}
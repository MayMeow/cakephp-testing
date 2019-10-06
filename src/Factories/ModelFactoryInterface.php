<?php

namespace MayMeow\Testing\Factories;

interface ModelFactoryInterface
{
    /**
     * Method get
     * @param array|null $data
     * @return \Cake\Datasource\EntityInterface|false
     */
    public function get(array $data = null);
}

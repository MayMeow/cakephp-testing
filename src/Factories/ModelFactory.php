<?php
/**
 * Model factory
 * @package MayMeow\Testing
 * @author May (MayMeow) https://github.com/MayMeow
 * @since 1.0
 * @license MIT
 */

namespace MayMeow\Testing\Factories;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Faker\Factory;
use Faker\Provider\Text;

class ModelFactory
{
    /**
     * @var \Cake\ORM\Table $table
     */
    protected $table;

    /**
     * @var \Faker\Factory $faker
     */
    protected $faker;

    /**
     * @var array $data
     */
    protected $data;

    /**
     * ModelFactory constructor.
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        $this->faker = Factory::create();

        $this->data = $data;
    }

    /**
     * Method define
     *
     * @param $table
     * @param callable $promise
     * @return \Cake\Datasource\EntityInterface|false
     */
    public function define($table, callable $promise)
    {
        $this->_getTable($table);

        $entity = $promise($this->faker);
        $entity = $this->_updateModelData($entity);

        if (is_array($entity)) {
            foreach ($entity as $k => $v) {

                if (is_callable($v)) {
                    $entity[$k] = $v();
                };

            }
        }

        $entity = $this->table->newEntity($entity);

        return $this->table->save($entity);
    }

    /**
     * Method _updateModelData
     * Update model data with data defined on function call
     *
     * @param $currentModelData
     * @return mixed
     */
    protected function _updateModelData($currentModelData)
    {
        if (is_array($this->data)) {
            foreach ($this->data as $k => $v) {
                $currentModelData[$k] = $v;
            }
        }

        return $currentModelData;
    }

    /**
     * Method _getTable
     *
     * @param $table
     * @return Table
     */
    protected function _getTable($table) {
        if ($this->table == null) {
            $this->table = TableRegistry::getTableLocator()->get($table);
        }

        return $this->table;
    }

    /**
     * Method gettable
     *
     * @return Table
     */
    public function getTale()
    {
        return $this->table;
    }
}

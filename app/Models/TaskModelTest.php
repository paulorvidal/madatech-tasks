<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TaskModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $migrateOnce = false;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testInsertTarefaValida()
    {
        $model = new TaskModel();
        $data = [
            'title' => 'Configurar servidor',
            'description' => 'Testando a inserção válida',
            'status' => 'pendente'
        ];

        $result = $model->insert($data);

        $this->assertIsNumeric($result);
    }

    public function testFaltaDeTituloGeraErroDeValidacao()
    {
        $model = new TaskModel();
        $data = [
            'description' => 'Tarefa sem título',
            'status' => 'pendente'
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);

        $this->assertArrayHasKey('title', $model->errors());
    }

    public function testStatusInvalidoGeraErroDeValidacao()
    {
        $model = new TaskModel();
        $data = [
            'title' => 'Tarefa com status errado',
            'status' => 'atrasada'
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('status', $model->errors());
    }
}
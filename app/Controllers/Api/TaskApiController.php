<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;

class TaskApiController extends ResourceController
{
    protected $modelName = TaskModel::class;
    protected $format = 'json';

    // GET /api/tasks 
    public function index()
    {
        return $this->respond($this->model->orderBy('created_at', 'DESC')->findAll());
    }

    // GET /api/tasks/{id} 
    public function show($id = null)
    {
        $task = $this->model->find($id);

        if (!$task) {
            return $this->failNotFound('Tarefa não encontrada.');
        }

        return $this->respond($task);
    }

    // POST /api/tasks 
    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        if (!$this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondCreated([
            'id' => $this->model->getInsertID(),
            'message' => 'Tarefa criada com sucesso!'
        ]);
    }

    // PUT /api/tasks/{id}
    public function update($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Tarefa não encontrada.');
        }

        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondUpdated(['id' => $id, 'message' => 'Tarefa atualizada com sucesso!']);
    }

    // DELETE /api/tasks/{id}
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Tarefa não encontrada.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => $id, 'message' => 'Tarefa excluída com sucesso!']);
    }
}
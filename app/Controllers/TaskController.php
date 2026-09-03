<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;
class TaskController extends BaseController
{
    private TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    public function index()
    {
        $data = [
            'tasks' => $this->taskModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('tasks/index', $data);
    }
    public function new()
    {
        return view('tasks/create');
    }

    public function create()
    {
        $postData = $this->request->getPost();
        if (!$this->taskModel->insert($postData)) {
            return redirect()->back()->withInput()->with('errors', $this->taskModel->errors());
        }

        return redirect()->to('/tasks')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(int $id)
    {
        $task = $this->taskModel->find($id);

        if (!$task) {
            throw PageNotFoundException::forPageNotFound('Tarefa não encontrada.');
        }
        return view('tasks/edit', ['task' => $task]);
    }

    public function update(int $id)
    {
        if (!$this->taskModel->find($id)) {
            throw PageNotFoundException::forPageNotFound('Tarefa não encontrada.');
        }

        $postData = $this->request->getRawInput(); // Captura os dados do método PUT

        if (!$this->taskModel->update($id, $postData)) {
            return redirect()->back()->withInput()->with('errors', $this->taskModel->errors());
        }

        return redirect()->to('/tasks')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function delete(int $id)
    {
        if (!$this->taskModel->find($id)) {
            throw PageNotFoundException::forPageNotFound('Tarefa não encontrada.');
        }
        $this->taskModel->delete($id);

        return redirect()->to('/tasks')->with('success', 'Tarefa excluída com sucesso!');
    }
}
<?php

declare(strict_types=1);

namespace App\Models;
use CodeIgniter\Model;

class TaskModel extends Model
{
    // Mapeamento da Tabela
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $allowedFields = ['title', 'description', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[pendente,em andamento,concluída]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'O campo título é obrigatório.',
            'min_length' => 'O título deve ter no mínimo 3 caracteres.',
            'max_length' => 'O título não pode exceder 255 caracteres.'
        ],
        'status' => [
            'required' => 'O campo status é obrigatório.',
            'in_list' => 'O status deve ser: pendente, em andamento ou concluída.'
        ]
    ];

    protected $skipValidation = false;

}
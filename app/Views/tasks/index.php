<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tarefas</h2>
            <a href="<?= base_url('tasks/new') ?>" class="btn btn-primary">Nova Tarefa</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Nenhuma tarefa encontrada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= esc($task['id']) ?></td>
                                    <td><?= esc($task['title']) ?></td>
                                    <td>
                                        <span
                                            class="badge bg-<?= $task['status'] === 'concluída' ? 'success' : ($task['status'] === 'em andamento' ? 'warning text-dark' : 'secondary') ?>">
                                            <?= esc(ucfirst($task['status'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('tasks/' . $task['id'] . '/edit') ?>"
                                            class="btn btn-sm btn-outline-secondary">Editar</a>

                                        <form action="<?= base_url('tasks/' . $task['id']) ?>" method="POST" class="d-inline"
                                            onsubmit="return confirm('Excluir esta tarefa?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
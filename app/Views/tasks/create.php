<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Nova Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Criar Nova Tarefa</h4>
                    </div>
                    <div class="card-body">

                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('tasks') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="<?= old('title') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea name="description" id="description" rows="4"
                                    class="form-control"><?= old('description') ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pendente" <?= old('status') === 'pendente' ? 'selected' : '' ?>>Pendente
                                    </option>
                                    <option value="em andamento" <?= old('status') === 'em andamento' ? 'selected' : '' ?>>
                                        Em Andamento</option>
                                    <option value="concluída" <?= old('status') === 'concluída' ? 'selected' : '' ?>>
                                        Concluída</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('tasks') ?>" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
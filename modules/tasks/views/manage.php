<h1>Manage Tasks</h1>

<?= flashdata() ?>

<?php if (empty($tasks)) return; ?>

<p><?= anchor('tasks/create', 'Create New Task', array('class' => 'button alt')) ?></p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Taks</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= $task->id ?></td>
                <td><?= out($task->task_title); ?></td>
                <td><?= out($task->status); ?></td>
                <td class="text-center">
                    <?= anchor('tasks/create/' . $task->id, 'Edit', array('class' => 'button alt')) ?>
                    <?= anchor('tasks/confirm_delete/' . $task->id, 'Delete Task', array('class' => 'button danger')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
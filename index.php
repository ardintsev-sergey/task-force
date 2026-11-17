<?php
require_once __DIR__ . '/vendor/autoload.php';
ini_set('assert.exception', 1);

use taskforce\logic\Task;

$task = new Task(Task::STATUS_NEW, 3, 1);

assert($task->getStatus() === Task::STATUS_NEW, 'current status');
assert($task->getPerformerId() === 3, 'performer id');
assert($task->getClientId() === 1, 'client id');

assert($task->getStatusMap()[Task::STATUS_NEW] === 'Новое', 'status map');
assert($task->getActionMap()[Task::ACTION_CANCEL] === 'Отменить', 'action map');

assert(
    $task->getAvailableActions(Task::STATUS_NEW) === [Task::ACTION_CANCEL, Task::ACTION_RESPONSE],
    'actions for new status'
);
assert(
    $task->getAvailableActions(Task::STATUS_IN_PROGRESS) === [Task::ACTION_DENY, Task::ACTION_COMPLETE],
    'actions for in-progress status'
);
assert($task->getAvailableActions(Task::STATUS_COMPLETE) === [], 'actions for complete status');

assert($task->getNextStatus('action_cancel') === Task::STATUS_CANCEL, 'cancel action');
assert($task->getNextStatus(Task::ACTION_COMPLETE) === Task::STATUS_COMPLETE, 'complete action');
assert($task->getNextStatus(Task::ACTION_RESPONSE) === null, 'response action');

echo "Все проверки успешно выполнены.\n";

<?php

namespace taskforce\logic;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'proceed';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_COMPLETE = 'complete';
    public const STATUS_EXPIRED = 'expired';

    public const ACTION_RESPONSE = 'action_response';
    public const ACTION_CANCEL = 'action_cancel';
    public const ACTION_DENY = 'action_deny';
    public const ACTION_COMPLETE = 'action_complete';

    private string $status;
    private ?int $performerId;
    private int $clientId;

    public function __construct(string $status, ?int $performerId, int $clientId)
    {
        if (!array_key_exists($status, $this->getStatusMap())) {
            throw new \InvalidArgumentException('Неизвестный статус задания');
        }

        $this->status = $status;
        $this->performerId = $performerId;
        $this->clientId = $clientId;
    }

    public function getStatusMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_COMPLETE => 'Выполнено',
            self::STATUS_EXPIRED => 'Просрочено',
        ];
    }

    public function getActionMap(): array
    {
        return [
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_DENY => 'Отказаться',
            self::ACTION_COMPLETE => 'Завершить',
        ];
    }

    public function getNextStatus(string $action): ?string
    {
        $map = [
            self::ACTION_COMPLETE => self::STATUS_COMPLETE,
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_DENY => self::STATUS_CANCEL,
            self::ACTION_RESPONSE => null,
        ];

        if (!array_key_exists($action, $map)) {
            throw new \InvalidArgumentException('Неизвестное действие');
        }

        return $map[$action];
    }

    public function getAvailableActions(string $status): array
    {
        $map = [
            self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPONSE],
            self::STATUS_IN_PROGRESS => [self::ACTION_DENY, self::ACTION_COMPLETE],
            self::STATUS_CANCEL => [],
            self::STATUS_COMPLETE => [],
            self::STATUS_EXPIRED => [],
        ];

        if (!array_key_exists($status, $map)) {
            throw new \InvalidArgumentException('Неизвестный статус задания');
        }

        return $map[$status];
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPerformerId(): ?int
    {
        return $this->performerId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }
}

<?php

namespace Neptunium\Core\Services;

use Exception;
use Neptunium\Core\ModelClasses\BaseService;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Notification;
use Neptunium\Core\ModelClasses\NotificationType;
use Neptunium\Core\ModelClasses\RenderParamsEnum;

class NotificationService extends BaseService {
    public static string $name = 'NotificationService';

    /**
     * @var Notification[]
     */
    private array $notifications = [];

    public function __construct(array $dependencies = []) {
        parent::__construct(self::$name, $dependencies);
    }

    public function initialize(): void {
        // TODO: Implement initialize() method.
    }

    public function addNotification(string $name, string $text, int $type = NotificationType::INFO ): void {
        $this->notifications[$name] = new Notification($text, $type);
    }

    /**
     * @throws Exception
     */
    public function getNotification(string $name): Notification {
        if (!isset($this->notifications[$name])) {
            throw new FrameworkException("Notification error!", "Notification '$name' does not exist");
        }
        return $this->notifications[$name];
    }

    /**
     * @return Notification[]
     */
    public function getNotifications(): array {
        return $this->notifications;
    }


    public function restoreNotifications(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (isset($_SESSION[RenderParamsEnum::NOTIFICATIONS->value])) {
            $this->notifications = json_decode($_SESSION[RenderParamsEnum::NOTIFICATIONS->value], true);
            return;
        }
        $this->notifications = [];
    }

    public function deleteNotification(string $name): void {
        unset($this->notifications[$name]);
    }

    public function clearNotifications(): void {
        $this->notifications = [];
        $this->saveNotifications();
    }

    public function saveNotifications(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $notificationsEncoded = json_encode($this->notifications, JSON_UNESCAPED_UNICODE);
        $_SESSION[RenderParamsEnum::NOTIFICATIONS->value] = $notificationsEncoded;
    }
}

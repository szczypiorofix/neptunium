<?php

namespace Neptunium\Services;

use Exception;
use Neptunium\ModelClasses\BaseService;
use Neptunium\ModelClasses\FrameworkException;
use Neptunium\ModelClasses\Notification;
use Neptunium\ModelClasses\NotificationType;

class NotificationService extends BaseService {
    const NOTIFICATIONS_KEY = 'notifications';

    /**
     * @var Notification[]
     */
    private array $notifications = [];

    public function __construct(array $dependencies = []) {
        parent::__construct('notification', $dependencies);
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
        if (isset($_SESSION[self::NOTIFICATIONS_KEY])) {
            $this->notifications = json_decode($_SESSION[self::NOTIFICATIONS_KEY], true);
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
        $_SESSION[self::NOTIFICATIONS_KEY] = $notificationsEncoded;
    }
}

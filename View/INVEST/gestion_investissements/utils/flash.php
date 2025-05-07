<?php
/**
 * stocke un message flash en session.
 * types : success | danger | warning | info
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash_messages'][] = [
        'type'    => $type,
        'message' => $message
    ];
}

/**
 * récupère et vide les messages flash.
 * @return array
 */
function getFlashes(): array {
    $flashes = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $flashes;
}

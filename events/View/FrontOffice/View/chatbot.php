<?php
header('Content-Type: application/json');
require_once '../../../config.php';

$pdo = config::getConnexion();
$input = strtolower(trim($_GET['q'] ?? ''));
$response = "❓ I didn't quite catch that. Try asking things like:\n- Next event\n- Events on May 5\n- Events in Paris\n- All events";

// Normalize input
$input = preg_replace('/\s+/', ' ', $input);

// Date detection (format: YYYY-MM-DD or Month Day)
if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $input, $matches)) {
    $date = $matches[1];
    $stmt = $pdo->prepare("SELECT nom_event, date_event, lieu FROM events WHERE DATE(date_event) = ?");
    $stmt->execute([$date]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($events) {
        $response = "📅 Events on *$date*:\n";
        foreach ($events as $e) {
            $response .= "- {$e['nom_event']} at {$e['lieu']}\n";
        }
    } else {
        $response = "📭 No events found on *$date*.";
    }
}

// Location detection
elseif (preg_match('/in ([a-z\s]+)/i', $input, $matches)) {
    $location = trim($matches[1]);
    $stmt = $pdo->prepare("SELECT nom_event, date_event FROM events WHERE lieu LIKE ? AND date_event >= CURDATE()");
    $stmt->execute(["%$location%"]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($events) {
        $response = "📍 Events in *$location*:\n";
        foreach ($events as $e) {
            $response .= "- {$e['nom_event']} on {$e['date_event']}\n";
        }
    } else {
        $response = "📭 No events found in *$location*.";
    }
}

// Upcoming (next) event
elseif (strpos($input, 'next event') !== false || strpos($input, 'upcoming') !== false) {
    $stmt = $pdo->query("SELECT nom_event, date_event, lieu FROM events WHERE date_event >= CURDATE() ORDER BY date_event ASC LIMIT 1");
    if ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response = "🔜 Next event: *{$event['nom_event']}* on *{$event['date_event']}* at *{$event['lieu']}*.";
    } else {
        $response = "📭 No upcoming events found.";
    }
}

// All upcoming events
elseif (strpos($input, 'all events') !== false || strpos($input, 'list events') !== false) {
    $stmt = $pdo->query("SELECT nom_event, date_event FROM events WHERE date_event >= CURDATE() ORDER BY date_event ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($events) {
        $response = "📋 Upcoming Events:\n";
        foreach ($events as $e) {
            $response .= "- {$e['nom_event']} ({$e['date_event']})\n";
        }
    } else {
        $response = "📭 No events scheduled.";
    }
}

// This week's events
elseif (strpos($input, 'this week') !== false) {
    $stmt = $pdo->query("SELECT nom_event, date_event FROM events WHERE YEARWEEK(date_event, 1) = YEARWEEK(CURDATE(), 1)");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($events) {
        $response = "📆 Events this week:\n";
        foreach ($events as $e) {
            $response .= "- {$e['nom_event']} ({$e['date_event']})\n";
        }
    } else {
        $response = "📭 No events this week.";
    }
}

// Event count
elseif (strpos($input, 'how many events') !== false || strpos($input, 'number of events') !== false) {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM events WHERE date_event >= CURDATE()");
    $count = $stmt->fetchColumn();
    $response = "🔢 There are *$count* upcoming event(s).";
}

echo json_encode(['response' => $response]);

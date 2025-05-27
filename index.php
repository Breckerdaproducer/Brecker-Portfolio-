<?php
function sendToTelegramWithCurl($botToken, $chatId, $message) {
    // Use IP address to bypass DNS issues
    $url = "https://149.154.167.99/bot{$botToken}/sendMessage";
    // Alternative IPs: 149.154.167.197, 149.154.167.198, 149.154.167.220
    
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 120);
    
    // Force IPv4 and set DNS servers
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RESOLVE, ['api.telegram.org:443:149.154.167.99']);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_errno($ch);
    $curlErrorMsg = curl_error($ch);
    
    curl_close($ch);
    
    if ($curlError) {
        return ['ok' => false, 'error' => "cURL Error #{$curlError}: {$curlErrorMsg}"];
    }
    
    if ($httpCode !== 200) {
        return ['ok' => false, 'error' => "HTTP Error: {$httpCode}"];
    }
    
    $decoded = json_decode($result, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['ok' => false, 'error' => 'Invalid JSON response'];
    }
    
    return $decoded ?: ['ok' => false, 'error' => 'Empty response'];
}

// Usage example for name and tracking ID
$botToken = '7743998095:AAG1G1qAtY-HSGX_5pdHeh-KA-6ueGV0kKA';
$chatId = '-1002675788685';

// Sanitize input data (especially important if coming from form)
$name = htmlspecialchars("John Doe");
$trackingId = htmlspecialchars("TRK123456789");

$message = "📦 <b>New Tracking Submission</b>\n\n";
$message .= "👤 <b>Name:</b> {$name}\n";
$message .= "🔢 <b>Tracking ID:</b> <code>{$trackingId}</code>\n";
$message .= "⏰ <b>Time:</b> " . date('Y-m-d H:i:s');

$result = sendToTelegramWithCurl($botToken, $chatId, $message);

if ($result && isset($result['ok']) && $result['ok']) {
    echo "Tracking information sent successfully!";
} else {
    echo "Error: " . ($result['error'] ?? ($result['description'] ?? 'Unknown error'));
}
?>

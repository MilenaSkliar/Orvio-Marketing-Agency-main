<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
function respond(int $code, bool $success, string $message): never {
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, false, 'Please submit the enquiry form.');
if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 25000) respond(413, false, 'Your enquiry is too large.');
$configSource = (string)file_get_contents(__DIR__ . '/config/config.js');
preg_match('/window\.SITE_CONFIG\s*=\s*(\{.*\});\s*window\.siteConfigReady/s', $configSource, $configMatch);
$config = json_decode($configMatch[1] ?? '', true);
if (!is_array($config) || empty($config['form']['enabled'])) respond(503, false, 'Enquiries are temporarily unavailable.');
if (!empty($_POST['website'])) respond(422, false, 'Unable to accept this enquiry.');
function field(string $key, int $limit): string {
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) respond(422, false, 'Please check the form fields.');
    $value = trim($value);
    if (strlen($value) > $limit) respond(422, false, 'A form field is too long.');
    return $value;
}
$name = field('name', 300);
$email = field('email', 254);
$company = field('company', 450);
$budget = field('budget', 100);
$message = field('message', 15000);
$consent = field('consent', 10);
if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $email) || strlen($message) < 20 || $consent !== 'yes') respond(422, false, 'Please enter your name, a valid email, a message of at least 20 characters and privacy consent.');
$budgets = ['Under $1,000', '$1,000–$3,000', '$3,000–$10,000', '$10,000+', 'Let’s discuss'];
if (!in_array($budget, $budgets, true)) respond(422, false, 'Please choose a budget range.');
$services = $_POST['services'] ?? [];
$allowed = ['Google Ads', 'Meta Advertising', 'Strategy & Creative', 'Collaboration'];
if (!is_array($services) || count($services) > 4) respond(422, false, 'Please check your selected services.');
foreach ($services as $service) if (!is_string($service) || !in_array($service, $allowed, true)) respond(422, false, 'Please select a valid service.');
$recipient = (string)($config['email'] ?? '');
if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $recipient) || str_ends_with(strtolower($recipient), '.example')) respond(503, false, 'The enquiry mailbox is not configured yet. Please try again later.');
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '' && strtolower((string)parse_url($origin, PHP_URL_HOST)) !== strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? '')[0])) respond(403, false, 'Please submit the form from this website.');
session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
$now = time();
if ($now - ($_SESSION['last_enquiry_attempt'] ?? 0) < 30) respond(429, false, 'Please wait a moment before trying again.');
$_SESSION['last_enquiry_attempt'] = $now;
$subject = str_replace(["\r", "\n"], '', (string)($config['form']['subject'] ?? 'New website enquiry'));
$body = "Name: $name\nEmail: $email\nCompany: $company\nBudget: $budget\nServices: " . implode(', ', $services) . "\nPrivacy consent: yes\n\n$message\n";
$headers = ['From' => $recipient, 'Reply-To' => $email, 'MIME-Version' => '1.0', 'Content-Type' => 'text/plain; charset=UTF-8'];
$sent = mail($recipient, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
if (!$sent) respond(502, false, 'Your enquiry could not be accepted by the email server. Please try again later.');
respond(200, true, (string)$config['form']['successMessage']);

<?php

declare(strict_types=1);

$message = trim((string) ($message ?? 'Your account does not have permission to access this page.'));
$authenticator = function_exists('auth') ? auth('session') : null;
$isLoggedIn = $authenticator !== null && $authenticator->loggedIn();
$primaryUrl = site_url('/');
$primaryLabel = $isLoggedIn ? 'Return to Home' : 'Go to Login';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden - InventoryV2</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(14, 165, 233, 0.14), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #eef6fb 100%);
        }

        .forbidden-shell {
            width: min(100%, 720px);
        }

        .forbidden-card {
            padding: 32px;
            border: 1px solid rgba(14, 165, 233, 0.18);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .forbidden-code {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .forbidden-card h1 {
            margin: 0;
            font-size: clamp(2rem, 5vw, 3rem);
            line-height: 1.05;
            color: #0f172a;
        }

        .forbidden-copy {
            margin: 0;
            color: #475569;
            font-size: 1rem;
            line-height: 1.7;
        }

        .forbidden-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 640px) {
            .forbidden-card {
                padding: 24px;
            }

            .forbidden-actions {
                flex-direction: column;
            }

            .forbidden-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <main class="forbidden-shell">
        <section class="forbidden-card stack-lg">
            <div class="stack-sm">
                <span class="forbidden-code">403 Forbidden</span>
                <h1>Access denied</h1>
                <p class="forbidden-copy"><?= esc($message) ?></p>
            </div>

            <div class="forbidden-actions">
                <a class="btn btn-primary" href="<?= esc($primaryUrl) ?>"><?= esc($primaryLabel) ?></a>
                <button type="button" class="btn btn-outline" onclick="window.history.back()">Go Back</button>
            </div>
        </section>
    </main>
</body>
</html>

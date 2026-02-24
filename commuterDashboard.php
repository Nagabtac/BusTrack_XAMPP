<?php
session_start();

// 1. SECURITY CHECK: If user is not logged in, kick them back to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: Auth.php");
    exit();
}

// 2. GET USER DATA: Use the session username for the greeting
$userName = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commuter Dashboard - Active Booking</title>
    <style>
        :root {
            --bg-dark: #0f0f0f;
            --bar-bg: #1a1a1a;
            --accent-pink: #ff4081;
            --accent-orange: #ffa726;
            --danger-red: #ff5252;
            --text-white: #ffffff;
        }

        body, html {
            margin: 0; padding: 0;
            height: 100%; width: 100%;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--bg-dark);
            color: var(--text-white);
            overflow: hidden;
        }

        /* Top Bar Layout */
        .top-bar {
            height: 90px;
            background: var(--bar-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            border-bottom: 2px solid #333;
        }

        .booking-details {
            display: flex;
            gap: 25px;
        }

        .info-group {
            display: flex;
            flex-direction: column;
        }

        .label {
            color: var(--accent-orange);
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .value {
            color: var(--accent-pink);
            font-size: 18px;
            font-family: 'Courier New', monospace;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* User Greeting Style */
        .user-greeting {
            color: var(--text-white);
            margin-right: 15px;
            font-size: 14px;
        }

        /* Buttons */
        .btn-cancel {
            background: transparent;
            color: var(--danger-red);
            border: 2px solid var(--danger-red);
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: var(--danger-red);
            color: white;
        }

        .settings-icon {
            font-size: 24px;
            color: #666;
            cursor: pointer;
            text-decoration: none;
        }

        .settings-icon:hover { color: var(--accent-pink); }

        /* Map Area */
        .map-area {
            height: calc(100vh - 92px);
            position: relative;
            background: #111;
            background-image: linear-gradient(#222 1px, transparent 1px), 
                            linear-gradient(90deg, #222 1px, transparent 1px);
            background-size: 40px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-msg {
            color: #333;
            font-size: 2.5rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .recenter-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: #1a1a1a;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .recenter-btn:hover {
            background: var(--accent-orange);
        }
    </style>
</head>
<body>

    <header class="top-bar">
        <div class="booking-details">
            <div class="info-group">
                <span class="label">User</span>
                <span class="value"><?php echo $userName; ?></span>
            </div>
            <div class="info-group">
                <span class="label">Bus #</span>
                <span class="value">B-402</span>
            </div>
            <div class="info-group">
                <span class="label">Plate</span>
                <span class="value">ABC-1234</span>
            </div>
            <div class="info-group">
                <span class="label">Destination</span>
                <span class="value">SM Mall - North</span>
            </div>
            <div class="info-group">
                <span class="label">ETA</span>
                <span class="value">08 Mins</span>
            </div>
        </div>

        <div class="actions">
            <button class="btn-cancel" onclick="confirmCancel()">CANCEL TICKET</button>
            <a href="logout.php" class="settings-icon" title="Logout">🚪</a>
        </div>
    </header>

    <main class="map-area">
        <div class="map-msg">Map Area</div>

        <button class="recenter-btn" title="Recenter Location">
            ⊕
        </button>
    </main>

    <script>
        function confirmCancel() {
            if(confirm("Are you sure you want to cancel your bus booking?")) {
                alert("Ticket Cancelled.");
                // You can redirect to a specific cancel script here
            }
        }
    </script>

</body>
</html>
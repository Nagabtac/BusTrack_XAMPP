<?php
// SETTINGS: Toggle 'driver' or 'commuter' to see the layout change
$view = 'driver'; 

// Mock Data
$bus = ["id" => "B-402", "plate" => "ABC-1234", "dest" => "Central Hub", "eta" => "8 mins"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($view); ?> Dashboard</title>
    <style>
        :root {
            --bg-dark: #121212;
            --topbar-bg: #1e1e1e;
            --sidebar-bg: #181818;
            --text-main: #ffffff;
            --accent-pink: #ff4081;
            --accent-yellow: #ffc107;
            --border: #333;
        }

        body, html {
            margin: 0; padding: 0;
            height: 100%; width: 100%;
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg-dark);
            color: var(--text-main);
            overflow: hidden;
        }

        /* Top Bar Layout */
        .top-bar {
            height: 70px;
            background: var(--topbar-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
        }

        .info-area { display: flex; gap: 20px; align-items: center; }
        .label { color: var(--accent-yellow); font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .value { color: var(--accent-pink); font-family: monospace; font-size: 16px; }

        .settings-icon {
            cursor: pointer;
            font-size: 24px;
            color: var(--accent-pink);
            transition: transform 0.3s;
        }
        .settings-icon:hover { transform: rotate(45deg); }

        /* Main Container */
        .main-container {
            display: flex;
            height: calc(100vh - 71px);
        }

        /* Sidebar (Driver Only) */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 20px;
        }
        .sidebar h3 { color: var(--accent-pink); margin-top: 0; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
        .commuter-item {
            padding: 10px;
            margin: 5px 0;
            background: #252525;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Map Area & Recenter */
        .map-area {
            flex: 1;
            position: relative;
            background: #1a1a1a;
            background-image: radial-gradient(#333 1px, transparent 1px);
            background-size: 20px 20px; /* Simulated map grid */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-label { color: #444; font-size: 2rem; font-weight: bold; text-transform: uppercase; }

        /* Recenter Button (Google Style) */
        .recenter-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: white;
            color: #444;
            border: none;
            padding: 12px 18px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .recenter-btn:hover { background: var(--accent-yellow); }
    </style>
</head>
<body>

    <header class="top-bar">
        <div class="info-area">
            <?php if($view == 'commuter'): ?>
                <div><span class="label">Bus:</span> <span class="value"><?php echo $bus['id']; ?></span></div>
                <div><span class="label">Plate:</span> <span class="value"><?php echo $bus['plate']; ?></span></div>
                <div><span class="label">To:</span> <span class="value"><?php echo $bus['dest']; ?></span></div>
                <div><span class="label">ETA:</span> <span class="value"><?php echo $bus['eta']; ?></span></div>
            <?php else: ?>
                <div><span class="label">Mode:</span> <span class="value">Driver Terminal</span></div>
            <?php endif; ?>
        </div>
        <div class="settings-icon" title="Settings">⚙</div>
    </header>

    <div class="main-container">
        <?php if($view == 'driver'): ?>
            <aside class="sidebar">
                <h3>Commuters</h3>
                <div class="commuter-item">👤 Alice (Accepted)</div>
                <div class="commuter-item">👤 Bob (Accepted)</div>
                <div class="commuter-item">👤 Charlie (Accepted)</div>
            </aside>
        <?php endif; ?>

        <main class="map-area">
            <div class="map-label">Map Area</div>
            
            <button class="recenter-btn">
                <span>⊕</span> RECENTER
            </button>
        </main>
    </div>

</body>
</html>
<?php
session_start();
require "config.php";

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE `key` = ?");
$stmt->execute([$_SESSION["key"]]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 w-96">
        <div class="flex items-center mb-6">
            <i class="fas fa-user-circle text-4xl text-green-400"></i>
            <h2 class="text-2xl font-bold ml-4">Dashboard</h2>
        </div>

        <div class="space-y-4">
            <p class="flex items-center">
                <i class="fas fa-key text-green-400 mr-2"></i>
                <strong>Your Key:</strong> 
                <span class="ml-2 text-gray-300"><?= htmlspecialchars($user['key']) ?></span>
            </p>
            <p class="flex items-center">
                <i class="fas fa-desktop text-blue-400 mr-2"></i>
                <strong>Device ID:</strong> 
                <span class="ml-2 text-gray-300"><?= htmlspecialchars($user['device_id']) ?></span>
            </p>
            <p class="flex items-center">
                <i class="fas fa-clock text-yellow-400 mr-2"></i>
                <strong>Expires At:</strong> 
                <span class="ml-2 text-gray-300"><?= $user['expires_at'] ?></span>
            </p>
        </div>

        <div class="mt-6">
            <a href="logout.php" class="w-full flex justify-center items-center bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </div>
</body>
</html>


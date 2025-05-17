<?php
session_start();
require "config.php";
require "device.php";

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $key = $_POST["key"]; 
    $deviceID = getDeviceID();

    try {
       
        $query = "SELECT * FROM users WHERE `key` = '$key' AND expires_at > NOW()";
        $result = $pdo->query($query); 
        $user = $result->fetch();

        if ($user) {
            if ($user['device_id'] === NULL) {
               
                $updateQuery = "UPDATE users SET device_id = '$deviceID' WHERE `key` = '$key'";
                $pdo->query($updateQuery);
                $_SESSION["loggedin"] = true;
                $_SESSION["key"] = $key;
                header("Location: dashboard.php");
                exit;
            } elseif ($user['device_id'] === $deviceID) {
                $_SESSION["loggedin"] = true;
                $_SESSION["key"] = $key;
                header("Location: dashboard.php");
                exit;
            } else {
                $errorMessage = "Access denied: Key is already used on another device.";
            }
        } else {
            $errorMessage = "Invalid or expired key!";
        }
    } catch (PDOException $e) {
       
        $errorMessage = "Database error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center">

    <div class="bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md relative">
        <h1 class="text-2xl font-bold text-center mb-6 flex items-center justify-center">
            <i class="fas fa-user-lock text-blue-500 mr-2"></i> Secure Login
        </h1>

        <?php if (!empty($errorMessage)) : ?>
            <div id="errorPopup" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-gray-800 text-white p-6 rounded-lg shadow-lg text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                    <p class="text-lg"><?php echo $errorMessage; ?></p>
                    <button onclick="closePopup()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">
                        Close
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div class="relative">
                <i class="fas fa-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    name="key" 
                    placeholder="Enter your key" 
                    required
                    class="w-full bg-gray-800 text-white rounded-lg pl-10 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-gray-700"
                >
            </div>
            <button 
                type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg transition-all duration-300">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>
        <p class="text-center text-gray-400 text-sm mt-6">
            Need help? <a href="https://t.me/enzosrs" target="_blank" class="text-blue-400 hover:underline">Contact Support</a>
        </p>
    </div>

    <script>
    
        function closePopup() {
            document.getElementById('errorPopup').remove();
        }
    </script>

</body>
</html>

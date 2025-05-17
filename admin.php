<?php
session_start();
require "config.php";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['generate_key'])) {
    
        $key = empty($_POST['custom_key']) ? bin2hex(random_bytes(6)) : $_POST['custom_key'];

        
        $days = isset($_POST['expiry_days']) && is_numeric($_POST['expiry_days']) ? $_POST['expiry_days'] : 3;
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$days days"));

        try {
          
            $stmt = $pdo->prepare("INSERT INTO users (`key`, expires_at) VALUES (?, ?)");
            $stmt->execute([$key, $expiresAt]);
            $successMessage = "Key generated successfully!";
        } catch (Exception $e) {
            $errorMessage = "Failed to generate key: " . $e->getMessage();
        }
    }

   
    if (isset($_POST['delete_key'])) {
        $deleteKey = $_POST['delete_key'];
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE `key` = ?");
            $stmt->execute([$deleteKey]);
            $successMessage = "Key deleted successfully!";
        } catch (Exception $e) {
            $errorMessage = "Failed to delete key: " . $e->getMessage();
        }
    }

   
    if (isset($_POST['edit_timer'])) {
        $newExpiry = $_POST['new_expiry'];
        $keyToUpdate = $_POST['key_to_update'];

        try {
            $stmt = $pdo->prepare("UPDATE users SET expires_at = ? WHERE `key` = ?");
            $stmt->execute([$newExpiry, $keyToUpdate]);
            $successMessage = "Key expiration updated successfully!";
        } catch (Exception $e) {
            $errorMessage = "Failed to update expiration: " . $e->getMessage();
        }
    }
}


$stmt = $pdo->query("SELECT * FROM users");
$keys = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Admin Panel</h2>

 
    <?php if ($successMessage): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

   
    <form method="POST" class="mb-6 p-4 bg-gray-50 rounded-lg">
        <label class="block mb-2 text-lg">Enter Custom Key (Optional):</label>
        <input type="text" name="custom_key" class="w-full p-2 border rounded-md" placeholder="Leave empty for a random key">

        <label class="block mt-4 mb-2 text-lg">Select Expiry (Days):</label>
        <input type="number" name="expiry_days" class="w-full p-2 border rounded-md" min="1" max="365" value="7">

        <button type="submit" name="generate_key" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
            Generate Key
        </button>
    </form>


    <h3 class="text-xl font-semibold mt-6">All Keys</h3>
    <table class="w-full mt-4 border-collapse border border-gray-300">
        <tr class="bg-gray-200">
            <th class="border p-2">Key</th>
            <th class="border p-2">Device ID</th>
            <th class="border p-2">Expires At</th>
            <th class="border p-2">Actions</th>
        </tr>
        <?php foreach ($keys as $key) { ?>
            <tr class="text-center bg-white">
                <td class="border p-2"><?= htmlspecialchars($key['key']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($key['device_id'] ?? 'Not assigned') ?></td>
                <td class="border p-2"><?= htmlspecialchars($key['expires_at']) ?></td>
                <td class="border p-2 flex justify-center space-x-2">
              
                    <form method="POST">
                        <input type="hidden" name="delete_key" value="<?= htmlspecialchars($key['key']) ?>">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded">
                            Delete
                        </button>
                    </form>

                   
                    <form method="POST" class="flex space-x-2">
                        <input type="hidden" name="key_to_update" value="<?= htmlspecialchars($key['key']) ?>">
                        <input type="datetime-local" name="new_expiry" required class="border p-1 rounded-md">
                        <button type="submit" name="edit_timer" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded">
                            Update
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>

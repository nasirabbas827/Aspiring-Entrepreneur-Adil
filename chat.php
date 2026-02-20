<?php
include('config.php');
session_start();
if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['id'];
$other_id = intval($_GET['user_id']);

// Fetch other user info
$other_res = mysqli_query($conn,"SELECT full_name FROM users WHERE id=$other_id");
$other = mysqli_fetch_assoc($other_res);
if (!$other) { die("User not found."); }

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    mysqli_query($conn,"INSERT INTO private_messages (sender_id, receiver_id, message, is_read) 
        VALUES ('$user_id','$other_id','$msg', 0)");
}

// Mark messages from other user as read
mysqli_query($conn, "UPDATE private_messages SET is_read=1 WHERE sender_id=$other_id AND receiver_id=$user_id");

// Fetch messages
$messages = mysqli_query($conn,"SELECT pm.*, u.full_name AS sender_name
    FROM private_messages pm
    JOIN users u ON pm.sender_id = u.id
    WHERE (sender_id=$user_id AND receiver_id=$other_id) OR (sender_id=$other_id AND receiver_id=$user_id)
    ORDER BY sent_at ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Chat with <?= htmlspecialchars($other['full_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="./CSS/style.css">
<style>
    .chat-box { height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; background: #f8f9fa; border-radius: 10px; }
    .msg-left { background:#f1f1f1; border-radius:10px; padding:10px; margin-bottom:5px; width: fit-content; max-width:75%; }
    .msg-right { background:#d1e7dd; border-radius:10px; padding:10px; margin-bottom:5px; text-align:right; margin-left:auto; width: fit-content; max-width:75%; }
    .msg-time { font-size: 0.75rem; color:#555; }
</style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Chat with <?= htmlspecialchars($other['full_name']) ?></h4>
        <a href="messages.php" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Conversations</a>
    </div>

    <div id="chat-box" class="chat-box mb-3">
        <?php while($m=mysqli_fetch_assoc($messages)): ?>
            <div class="<?= $m['sender_id']==$user_id ? 'msg-right' : 'msg-left' ?>">
                <strong><?= htmlspecialchars($m['sender_name']) ?>:</strong> <?= htmlspecialchars($m['message']) ?>
                <br><span class="msg-time"><?= date('M d, Y H:i', strtotime($m['sent_at'])) ?></span>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST" class="d-flex">
        <input type="text" name="message" class="form-control me-2" placeholder="Type a message" required>
        <button class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Send</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto scroll to latest message
    var chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>
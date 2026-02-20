<?php
include('config.php');
session_start();
if (!isset($_SESSION['id'])) { 
    header("Location: login.php"); 
    exit(); 
}
$user_id = $_SESSION['id'];

// Fetch distinct conversations with last message info
$conversations = mysqli_query($conn, "
    SELECT 
        u.id AS other_user_id,
        u.full_name,
        u.role,
        m.message,
        m.sent_at,
        SUM(CASE WHEN m.receiver_id = $user_id AND m.is_read = 0 THEN 1 ELSE 0 END) AS unread_count
    FROM (
        SELECT sender_id, receiver_id, message, sent_at, is_read
        FROM private_messages
        WHERE sender_id = $user_id OR receiver_id = $user_id
    ) AS m
    JOIN users u ON (u.id = CASE WHEN m.sender_id = $user_id THEN m.receiver_id ELSE m.sender_id END)
    GROUP BY other_user_id
    ORDER BY MAX(m.sent_at) DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">
<style>
    .conversation-item { transition: 0.2s; }
    .conversation-item:hover { background-color: #f8f9fa; }
    .unread-badge { font-size: 0.8rem; }
    .last-msg { font-size: 0.85rem; color: #555; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 250px; display: inline-block; }
</style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Conversations</h3>
    </div>

    <?php if(mysqli_num_rows($conversations) == 0): ?>
        <p class="text-muted">No conversations yet. Start chatting with an agent.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php while($c = mysqli_fetch_assoc($conversations)): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center conversation-item">
                <div>
                    <a href="chat.php?user_id=<?= $c['other_user_id'] ?>" class="fw-bold"><?= htmlspecialchars($c['full_name']) ?></a>
                    <span class="last-msg ms-2"><?= htmlspecialchars($c['message']) ?></span>
                    <div class="text-muted small"><?= date('M d, H:i', strtotime($c['sent_at'])) ?></div>
                </div>
                <?php if($c['unread_count'] > 0): ?>
                    <span class="badge bg-danger rounded-pill unread-badge"><?= $c['unread_count'] ?> New</span>
                <?php endif; ?>
            </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
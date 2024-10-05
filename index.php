<?php
require_once 'classes/Database.php';

$db = new Database();

$results = $db->read('posts');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
</head>
<body>
    <h1>Posts</h1>
    <hr>
    <form method="post">
        <label>Title</label><br>
        <input type="text" name="title"><br>
        <label>Content</label><br>
        <textarea name="content" rows="7"></textarea><br>
        <label>Status</label><br>
        <select name="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </form>
    <hr>
    <table border="1">
        <thead>
            <th>Title</th>
            <th>Status</th>
        </thead>
        <tbody>
            <?php foreach($results as $result) { ?>
            <tr>
                <td><?php echo $result['title']; ?></td>
                <td><?php echo $result['status']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

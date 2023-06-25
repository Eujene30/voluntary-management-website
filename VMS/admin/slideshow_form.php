<?php
session_start();
require('../config/db.php');
require('../config/admin_menu.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}
  
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../config/error.php");
    exit();
}

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slide_caption = $_POST['slide_caption'];
    $event_id = $_POST['event_id']; 

    $slide_image = '';
    if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] === 0) {
        $pictureTmpName = $_FILES['slide_image']['tmp_name'];
        $pictureName = $_FILES['slide_image']['name'];
        $picturePath = '../img/' . $pictureName;

        if (move_uploaded_file($pictureTmpName, $picturePath)) {
            $slide_image = $picturePath;
        }
    }
    

    $insertQuery = "INSERT INTO homepage_slides (slide_image, slide_caption, event_id) VALUES (?,?,?)";
    $stmtInsert = $conn->prepare($insertQuery);
    $stmtInsert->bind_param('ssi', $slide_image, $slide_caption, $event_id);
    $stmtInsert->execute();

    if ($stmtInsert->affected_rows > 0) {
        echo "Slideshow added into database";
    } else {
        echo "Failed to add into database";
    }
    $stmtInsert->close();
    $conn->close();

    header("Location: homepage_slideshows.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/add_user.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <form action="slideshow_form.php" method="POST" enctype="multipart/form-data">
        <table>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <label for="slide_image">Event Picture:</label></td>
            <input type="file" name="slide_image" id="slide_image" accept="image/*" required></td>
           
            
            <label>Slide Caption: </label>
            <input type="text" name="slide_caption" id="slide_caption" required>
            
            <label>Event: </label>
            <select name="event_id" id="event_id" required>
                <?php
                    $eventQuery = "SELECT event_id, eventName FROM events";
                    $resultQuery = mysqli_query($conn, $eventQuery);

                    while($rowQuery = mysqli_fetch_assoc($resultQuery)) {
                        $event_id = $rowQuery['event_id'];
                        $eventName = $rowQuery['eventName'];
                        echo "<option value='$event_id'>$eventName</option>";
                    }
                ?>
            </select>
            
            <tr>
                <td colspan="2"><input type="submit" value="Add Slides +"></td>
            </tr>
        </table>
    </form>

    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>
</body>

  
</html>
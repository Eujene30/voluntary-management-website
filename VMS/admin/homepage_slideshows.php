<?php
require('../config/db.php');
require('../config/admin_menu.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteSlide'])) {
    $deleteSlide = $_POST['deleteSlide'];

    $queryDelete = "DELETE FROM homepage_slides WHERE slides_id = ?";
    $deleteStmt = $conn->prepare($queryDelete);
    $deleteStmt->bind_param('i', $deleteSlide);
    $deleteStmt->execute();

    if($deleteStmt->affected_rows > 0){
        echo "Deleted slideshow";
    } else {
        echo "Slideshow fail to be deleted";
    }

    $deleteStmt->close();
    header("Location: homepage_slideshows.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin</title>
  <link rel="stylesheet" href="../css/event_approval.css" />
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <h1 class="view-events">Slideshows</h1>
    <table>
        <thead>
            <tr>
            <th>Slide Image</th>
            <th>Slide Caption</th>
            <th>Action</th>
            <th></th>
            <th><a href="slideshow_form.php" class="add-user-btn">Add Slide</a></th>
            </tr>
        </thead>
        <tbody>
        <?php
                $querySlideshow = "SELECT * FROM homepage_slides";
                $resultSlideshow = mysqli_query($conn, $querySlideshow);

                while ($rowSlideshow = mysqli_fetch_assoc($resultSlideshow)){
                    ?> 
                        <tr>
                            <td><img src="<?php echo $rowSlideshow['slide_image']; ?>" alt="Event Picture" style="width: 100px;"></td>
                            <td><?php echo $rowSlideshow['slide_caption']; ?></td>
                            <td>
                                <form action="homepage_slideshows.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?');" >
                                    <input type="hidden" name="deleteSlide" value="<?php echo $rowSlideshow['slides_id']; ?>">
                                    <button type="submit" name="deleteBtn" class="reject"> Delete </button>
                                </form>
                            </td>    
                        
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <script>
        function backFunction() {
            window.location.href = "admin_dashboard.php";
        }
    </script>

</body>

  
</html>
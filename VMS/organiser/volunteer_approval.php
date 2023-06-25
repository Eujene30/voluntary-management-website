<?php
require('../config/db.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$userID = $_SESSION['user_id'];
$organiserID = $_SESSION['user_id'];

if(isset($_POST['approveVolunteer'])){
    $user_registrationID = $_POST['user_registrationID'];
    $query = "UPDATE user_registration SET status = 'Approved' WHERE register_id = $user_registrationID";
    mysqli_query($conn, $query);

    $getUserID = "SELECT user_id FROM user_registration WHERE register_id = $user_registrationID";
    $result = mysqli_query($conn, $getUserID);
    $row = mysqli_fetch_assoc($result);
    $userStatus = $row['user_id'];

    $updateUserStatus = "UPDATE users SET user_status = 'occupied' WHERE user_id = $userStatus";
    mysqli_query($conn, $updateUserStatus);

    $getUserData = "SELECT user_id, event_id, organiserID, name FROM user_registration WHERE register_id=?";
    $stmt = $conn->prepare($getUserData);
    $stmt->bind_param("i", $user_registrationID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $user_id = $row['user_id'];
    $event_id = $row['event_id'];
    $organiser_id = $organiserID;
    $name = $row['name'];

    $addUserAttendance = "INSERT INTO volunteer_attendance (event_id, user_id, user_name, organiserID, status) VALUES (?,?,?,?,'')";
    $stmt = $conn->prepare($addUserAttendance);
    $stmt->bind_param("iisi", $event_id, $user_id, $name, $organiser_id);
    try{ 
        $stmt->execute();
    } catch(mysqli_sql_exception $exclude) {
        echo "An error occurred";
    }

}

if(isset($_POST['rejectVolunteer'])){
    $user_registrationID = $_POST['user_registrationID'];
    $query = "UPDATE user_registration SET status = 'Rejected' WHERE register_id = $user_registrationID";
    mysqli_query($conn, $query);

    $getUserID = "SELECT user_id FROM user_registration WHERE register_id = ?";
    $stmt = $conn->prepare($getUserID);
    $stmt->bind_param("i", $user_registrationID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    $updateUserStatus = "UPDATE users SET user_status = 'available' WHERE user_id = ?";
    $stmt = $conn->prepare($updateUserStatus);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $deleteAttendance = "DELETE FROM volunteer_attendance WHERE event_id IN (SELECT event_id FROM user_registration WHERE register_id = ?)";
    $stmt = $conn->prepare($deleteAttendance);
    $stmt->bind_param("i", $user_registrationID);
    $stmt->execute();
    
    $deleteUser = "DELETE FROM user_registration WHERE register_id = ?";
    $stmt = $conn->prepare($deleteUser);
    $stmt->bind_param("i", $user_registrationID);
    $stmt->execute();

    
}
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/view_event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>
    <div class="container">
        <div class="sidebar">
            <?php  ?>
        </div>

        <div class="volunteers">
           <h1 class="header">Volunteer Approval</h1>
            <table>
                <thead>
                    <tr>
                        <th>Volunteer Name</th>
                        <th>Email</th>
                        <th>H/P</th>
                        <th>Address</th>
                        <th>DOB</th>
                        <th>Skills&Experience</th>
                        <th>Additional Comments</th>
                        <th>Registration Date</th> 
                        <th>Status</th>     
                        <th></th>
                        <th>Actions</th>        
                        <th></th>
                    </tr>
                </thead>    
                <tbody>
                    <?php
                        $query = "SELECT * FROM user_registration WHERE status = 'Pending' AND organiserID = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $organiserID);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = mysqli_fetch_assoc($result)){
                                    
                        ?> 
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['date_of_birth']; ?></td>
                                <td><?php echo $row['skills_experience']; ?></td>
                                <td><?php echo $row['additional_comments'];?></td>
                                <td><?php echo $row['registration_date'];?></td>
                                <td><?php echo $row['status'];?></td>
                                <td></td>
                                <td>
                                    <form action="volunteer_approval.php" method="post" >
                                        <input type="hidden" name="user_registrationID" value="<?php echo $row['register_id']; ?>">
                                        <button type="submit" name="approveVolunteer" class="approve"> Approve </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="volunteer_approval.php" method="post" >
                                        <input type="hidden" name="user_registrationID" value="<?php echo $row['register_id']; ?>">
                                        <button type="submit" name="rejectVolunteer" class="reject"> Reject </button>
                                    </form>
                                </td>   
                            </tr>
                        <?php 
                        } 
                    ?>
                </tbody> 
            </table>
            
            <h1 class="header">Volunteer List</h1>
             <table>
                <thead>
                    <tr>
                        <th>Volunteer Name</th>
                        <th>Email</th>
                        <th>H/P</th>
                        <th>Address</th>
                        <th>DOB</th>
                        <th>Skills&Experience</th>
                        <th>Additional Comments</th>
                        <th>Registration Date</th> 
                        <th>Status</th>     
                        <th></th>
                        <th>Actions</th>        
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $query = "SELECT * FROM user_registration WHERE status = 'Approved' AND organiserID = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $organiserID);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = mysqli_fetch_array($result)) {
                            ?> 
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['date_of_birth']; ?></td>
                                    <td><?php echo $row['skills_experience']; ?></td>
                                    <td><?php echo $row['additional_comments'];?></td>
                                    <td><?php echo $row['registration_date'];?></td>
                                    <td><?php echo $row['status'];?></td>
                                    <td></td>
                                    <td>
                                        <form action="volunteer_approval.php" method="post" >
                                            <input type="hidden" name="user_registrationID" value="<?php echo $row['register_id']; ?>">
                                            <button type="submit" name="rejectVolunteer" class="reject"> Reject </button>
                                        </form>
                                    </td>   
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
        
   
</body>
</html>
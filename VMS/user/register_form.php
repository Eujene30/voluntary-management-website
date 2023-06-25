<?php
session_start();
require('../config/db.php');
require('../config/menu.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : "";
$email = isset($_SESSION['email']) ? $_SESSION['email'] : "";

if(isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $_SESSION['event_id'] = $event_id;
}

$eventID = $_SESSION['event_id'];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];

    $query = "SELECT organiserID FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $organiserID = $event['organiserID'];


    
    $eventID = $_SESSION['event_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $skills_experience = $_POST['skills_experience'];
    $additional_comments = $_POST['additional_comments'];
    $registration_date = date('Y-m-d H:i:s');
    $status = 'Pending';

    $query = "INSERT INTO user_registration (event_id, name, email, phone, address, date_of_birth, skills_experience, additional_comments, registration_date, status, user_id, organiserID)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssssssii", $eventID, $name, $email, $phone, $address, $date_of_birth, $skills_experience, $additional_comments, $registration_date, $status, $user_id, $organiserID);
    $stmt->execute();


    $message = "A new volunteer submitted";
    $timestamp= date('Y-m-d H:i:s');
    $read_status = 0;

    $query = "INSERT INTO notifications (user_id, event_id, organiserID, message, created_at, read_status)
            VALUES (?,?,?,?,?,?)" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissi", $user_id , $eventID, $organiserID, $message, $timestamp, $read_status);
    $stmt->execute();

    header("Location: all_events.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Voluntree</title> 
    <link rel="stylesheet" href="../css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
   </head>
<body>
    
    
    <form action="register_form.php" method="post" class="register-form" enctype="multipart/form-data">
        <h1>Volunteer Registration</h1>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $username; ?>" class="register-input" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="register-input" required>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" class="register-input" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" class="register-input" required>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" class="register-input" required>

        <input type="hidden" id="event_id" name="event_id" value="<?php echo $event_id; ?>">

        <label for="skills_experience">Skills and Experience:</label><br>
        <textarea id="skills_experience" name="skills_experience" rows="4" cols="50" class="register-input" required></textarea>

        <label for="additional_comments">Additional Comments:</label><br>
        <textarea id="additional_comments" name="additional_comments" rows="4" cols="50" class="register-input"></textarea>
        
        <h5>*Registration form will be reviewed by organiser to decide if participant is qualified*</h5>
        
        <input type="submit" name="volunteerRegister" value="Register">
    </form>


    <div class="content"></div>
 
    
</body>
</html>
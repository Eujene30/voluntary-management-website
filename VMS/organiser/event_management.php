<?php
require('../config/db.php');
require('../organiser/organiser_dashboard.php');

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../config/login.php");
    exit();
}

$selectEventID = isset($_POST['eventSelect']) ? $_POST['eventSelect'] : null;
$eventDetails = null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['eventSelect'])) {
        $selectEventID = $_POST['eventSelect'];
        $_SESSION['selectedEventID'] = $selectEventID;

        $stmt = $conn->prepare('SELECT eventName, eventDate, eventLocation FROM events WHERE event_id = ? AND organiserID = ? AND approvalStatus = "Approved"');
        $stmt->bind_param('ii', $selectEventID, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $eventDetails = $result->fetch_assoc();
    }

    if(isset($_POST['attendanceMark'])) {
        $volunteerIDs = $_POST['user_id'];
        $event_id = $selectEventID;
        $attendanceStatuses = $_POST['attendanceStatus'];
        $attendanceIDs = $_POST['attendanceID'];

        foreach ($attendanceIDs as $key => $attendanceID) {
            $volunteer_id = $volunteerIDs[$key];
            $attendance_status = $attendanceStatuses[$attendanceID];

            $stmtUpdate = $conn->prepare('UPDATE volunteer_attendance SET status = ? WHERE attendance_id = ?');
            $stmtUpdate->bind_param("si", $attendance_status, $attendanceID);
            $stmtUpdate->execute();
        }
        if($stmtUpdate->affected_rows > 0) {
            echo '<p class="success-message">Attendance has been updated</p>';
        } else {
            echo '<p class="error-message">Fail to update attendance </p>';
        }
    }

    
    if(isset($_POST['createStation'])) {
        $stationNames = $_POST['stationName'];
        $eventID = $_POST['eventID'];
            

        $stmt = $conn->prepare('SELECT organiserID FROM volunteer_attendance WHERE event_id = ?');
        $stmt->bind_param('i', $eventID);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()) {
            $organiserID = $row['organiserID'];
            
            $stmtExisted = $conn->prepare('SELECT COUNT(*) as count FROM event_coordinations WHERE stationName =? AND organiserID =? AND event_id =?'); 
            $stmtExisted->bind_param('sii', $stationName, $organiserID, $eventID);

            $stmtInserted = $conn->prepare('INSERT INTO event_coordinations (stationName, organiserID, event_id) VALUES (?,?,?)');
            $stmtInserted->bind_param('sii', $stationName, $organiserID, $eventID);

            foreach($stationNames as $stationName) {
                if(!empty($stationName)) {
                    $stmtExisted->execute();
                    $resultExisted = $stmtExisted->get_result();
                    $rowExisted = $resultExisted->fetch_assoc();
                    $countExisted = $rowExisted['count'];

                    if($countExisted == 0){
                        $stmtInserted->execute();
                        if($stmtInserted->affected_rows > 0) {
                            echo '<p>Station available ' .$stationName. '</p>';
                            $stationID = $stmtInserted->insert_id;
                        } else {
                            echo '<p>Station failed to create ' .$stationName. '</p>';
                        }
                    } else {
                        echo '<p>Station already available ' .$stationName. '</p>';
                    }
                }
            }
        }
    }

    if (isset($_POST['removeUser']) && isset($_POST['attendanceID'])) {
        $attendanceID = $_POST['attendanceID'];
        
        $stmtDelete = $conn->prepare('DELETE FROM event_volunteers WHERE attendance_id = ?');
        $stmtDelete->bind_param('i', $attendanceID);
        
        if ($stmtDelete->execute()) {
            echo '<p>Volunteer removed from the station.</p>';
        } else {
            echo '<p>Failed to remove volunteer from the station.</p>';
        }
    }

   
    
   

    
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
    
    <div class="event-container">
        <h1>Event Management</h1>
        <form action="event_management.php" method="post">
            <label class="select-event" for="">Select Event : </label>
            <select name="eventSelect" id="eventSelect">
                <?php
                    $stmt = $conn->prepare('SELECT event_id, eventName from events WHERE organiserID = ? AND approvalStatus = "Approved"');
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while($row = $result->fetch_assoc()) {
                        $eventID = $row['event_id'];
                        $eventName = $row['eventName'];
                        $selected = ($eventID == $selectEventID) ? 'selected' : '';
                        echo '<option value="' . $eventID . '" ' .$selected . '>' . $eventName . '</option>';
                    }

                ?>
            </select>
            <button type="submit" class="approve">Manage Event</button>

        </form>    

        <?php
        if(isset($_SESSION['selectedEventID'])) {
            $selectEventID = $_SESSION['selectedEventID'];

            $stmt = $conn->prepare('SELECT eventName, eventDate, eventLocation FROM events WHERE event_id = ? AND organiserID = ? AND approvalStatus = "Approved"');
            $stmt->bind_param('ii', $selectEventID, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $eventDetails = $result->fetch_assoc();

        }
            if (isset($selectEventID) && $eventDetails) { 
                ?>
                <div class="event-details">
                    <form action="event_management.php" method="POST">
                        <div class="label-container">
                            <label for="eventName" class="view-only">Event Name:</label>
                            <input type="text" id="eventName"  name="eventName" value="<?php echo $eventDetails['eventName']; ?>" readonly>

                            <label for="eventDate" class="view-only">Event Date:</label>
                            <input type="text" id="eventDate" name="eventDate" value="<?php echo $eventDetails['eventDate']; ?>" readonly>

                            <label for="eventLocation" class="view-only">Event Location:</label>
                            <input type="text" id="eventLocation"  name="eventLocation" value="<?php echo $eventDetails['eventLocation']; ?>" readonly>

                            <div class="attendance-list">
                                <h1>Volunteer Attendance</h1>
                                <table class="attendance-table">
                                    <thead>
                                        <tr>
                                            <th>Volunteer Name</th>
                                            <th>Attendance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $stmt = $conn->prepare('SELECT va.user_name, va.attendance_id, va.user_id, va.status from volunteer_attendance va
                                                                    INNER JOIN users u ON va.user_id = u.user_id WHERE va.event_id = ?');
                                            $stmt->bind_param('i', $selectEventID);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while($row = $result->fetch_assoc()) {
                                                $attendanceID = $row['attendance_id'];
                                                $volunteerName = $row['user_name'];
                                                $attendanceStatus = $row['status'];


                                                echo '<tr>';
                                                echo '<td>' . $volunteerName . '</td>';
                                                echo '<td>';
                                                echo '<input type="hidden" name="attendanceID[]" value="' . $attendanceID . '">';
                                                echo '<input type="hidden" name="user_id[]" value="' . $row['user_id'] . '">';
                                                echo '<input type="radio" class="radioBtn" id="present-' . $attendanceID . '" name="attendanceStatus[' . $attendanceID . ']" value="Present" ' . ($attendanceStatus === 'Present' ? 'checked' : '') . '>';
                                                echo '<label for="present' . $attendanceID . '" class="attendanceLabel" >Present</label>';
                                                echo '<input type="radio" class="radioBtn" id="absent-' . $attendanceID . '" name="attendanceStatus[' . $attendanceID . ']" value="Absent" ' . ($attendanceStatus === 'Absent' ? 'checked' : '') . '>';
                                                echo '<label for="absent' . $attendanceID . '" class="attendanceLabel" >Absent</label>';
                                                echo '</td>';
                                                echo '</tr>';
                                                
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <button type="submit" class="attendanceBtn" name="attendanceMark">Update Attendance</button>
                    </form>
                </div>

            <?php } elseif (isset($_POST['selectEventID'])) { ?>
                <p>No event found.</p>
            <?php } ?>
          
        <div class="event-coordination-container">
            <h1>Create Event Station</h1>
            <form action="event_management.php" method="POST">
                <input type="hidden" name="eventID" value="<?php echo $selectEventID; ?>">
                <input type="hidden" name="organiserID" value="<?php echo $organiserID; ?>">

                <table id="station_table">
                    <thead>
                        <tr>
                            <th>Station</th>
                            <th>Present</th>
                        </tr>
                    </thead>
                    <tbody id="station_body">
                        <tr class="station-row">
                            <td>
                                <input type="text" name="stationName[]" required>
                            </td>
                            <td>
                                <?php
                                    $stmt = $conn->prepare('SELECT va.user_name, va.user_id FROM volunteer_attendance 
                                                            va LEFT JOIN event_volunteers ev ON va.attendance_id = ev.attendance_id
                                                            WHERE va.event_id = ? AND va.status="Present" AND ev.attendance_id IS NULL');
                                    $stmt->bind_param('i', $selectEventID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $selectVolunteer = isset($_POST['present']) ? $_POST['present'] : [];

                                    while ($row = $result->fetch_assoc()) {
                                        $userName = $row['user_name'];
                                        $userID = $row['user_id'];

                                        if(in_array($userID, $selectVolunteer)){
                                            continue;
                                        }

                                        echo '<input type="checkbox" name="present[]" value="' . $userID .'">
                                        <label for="present-' . $userID . '">' . $userName . '</label><br>';
                                    }

                                   
                                    if (isset($_POST['present']) && is_array($_POST['present'])) {
                                        $selected_users = $_POST['present'];

                                        foreach($selected_users as $selected_usersID) {
                                            $stmtExisted = $conn->prepare('SELECT COUNT(*) as count FROM event_volunteers WHERE attendance_id = ? AND event_id=?');
                                            $stmtExisted->bind_param('ii', $selected_usersID, $selectEventID);
                                            $stmtExisted->execute();
                                            $resultExisted = $stmtExisted->get_result();
                                            $rowExisted = $resultExisted->fetch_assoc();
                                            $countExisted = $rowExisted['count'];

                                            if($countExisted == 0) {
                                                $stmtAttendance = $conn->prepare('SELECT user_name, attendance_id FROM volunteer_attendance WHERE user_id = ?');
                                                $stmtAttendance->bind_param('i', $selected_usersID); 
                                                $stmtAttendance->execute();
                                                $resultAttendance = $stmtAttendance->get_result();
                                                $rowAttendance = $resultAttendance->fetch_assoc();
                                                $userName = $rowAttendance['user_name'];
                                                $attendanceID = $rowAttendance['attendance_id'];

                                                $stmtInserted = $conn->prepare('INSERT INTO event_volunteers (stationName, name, attendance_id, event_id) 
                                                                                SELECT ?,?,?,? FROM DUAL WHERE NOT EXISTS 
                                                                                (SELECT * FROM event_volunteers WHERE attendance_id = ? AND event_id = ?)');
                                                $stmtInserted->bind_param('ssiiii', $stationName, $userName, $attendanceID, $selectEventID, $attendanceID, $selectEventID);
                                                $stmtInserted->execute();

                                                if($stmtInserted->affected_rows > 0) {
                                                    echo '<p>' .$userName. '</p>';
                                                } else {
                                                    echo '<p>' .$userName. '</p>';
                                                }
                                            }
                                            
                                            
                                        }
                                            
                                    }
                                    
                                

                                ?>
                            </td>
                            <td>
                                <button type="button" class="deleteBtn">X</button>
                            </td>
                        </tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tbody>
                </table>
                <button type="button" class="approve" id="addBtn">Add Station +</button>
                <button type="submit"  class="approve" name="createStation">Create Station</button>
            </form>
        </div>

        <div class="event-checkpoint-table">
            <h1>Checkpoints</h1>
                <table >
                    <thead>
                        <tr>
                            <td><th>Station</th></td>
                            <th>Attendee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           
                            <td>
                                <?php
                                    $stmt = $conn->prepare('SELECT ec.stationName, ev.name, ev.attendance_id FROM event_coordinations ec
                                                            INNER JOIN event_volunteers ev ON ec.stationName = ev.stationName 
                                                            WHERE ec.organiserID = ? AND ec.event_id = ?');
                                    $stmt->bind_param('ii', $user_id, $selectEventID);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $station_rows = '';

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';

                                            if ($station_rows != $row['stationName']) {
                                                echo '<td>'. $row['stationName'] . '</td>';
                                                
                                            } else {
                                                echo '<td></td>';
                                            }
                                            
                                            echo '<td>'. $row['name'] . '</td>';
                                            echo '<td>
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="attendanceID" value="' .$row['attendance_id']. '">
                                                        <input type="hidden" name="removeUser" value="1">
                                                        <button type="submit" class="deleteBtn" onclick="return confirm(\'Are you sure to remove user?\')">Remove</button>
                                                    </form>
                                                </td>
                                                <td></td>';
                                            
                                            echo '</tr>';

                                            $station_rows = $row['stationName'];
                                        }
                                        
                                        
                                    } else {
                                       echo '<tr></td><td><td colspan="1">No stations.</td><td></td></tr>';
                                    }

                                   
                                    echo '<td><td><td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="eventComplete" value="1">
                                                <button type="submit" class="attendanceBtn" onclick="return confirm(\'Confirm to complete event?\')">Event Complete</button>
                                            </form>
                                        </td></td></td>';

                                    if(isset($_POST['eventComplete'])) {
                                        $stmtUpdateUsersStatus = $conn->prepare('UPDATE users SET user_status = "available" 
                                                                                WHERE user_id IN (SELECT user_id FROM volunteer_attendance WHERE event_id =?)');
                                        $stmtUpdateUsersStatus->bind_param('i', $selectEventID);
                                        $stmtUpdateUsersStatus->execute();

                                        $stmtUpdateStatusEvent = $conn->prepare('UPDATE events SET approvalStatus = "Complete" WHERE event_id= ?');
                                        $stmtUpdateStatusEvent->bind_param('i', $selectEventID);
                                        $stmtUpdateStatusEvent->execute();
                                
                                        $stmtUpdateUsersAttendance = $conn->prepare('UPDATE volunteer_attendance SET status = "Complete" WHERE event_id = ?'); 
                                        $stmtUpdateUsersAttendance->bind_param('i', $selectEventID);
                                        $stmtUpdateUsersAttendance->execute();

                                        $stmtUpdateUsersRegistration = $conn->prepare('UPDATE user_registration SET status = "Complete" WHERE event_id = ?'); 
                                        $stmtUpdateUsersRegistration->bind_param('i', $selectEventID);
                                        $stmtUpdateUsersRegistration->execute();

                                        $volunteerHistory = $conn->prepare('INSERT INTO volunteer_history (event_id, user_id, organiserID, name, email, phone, 
                                                                            address, date_of_birth, skills_experience, additional_comments, 
                                                                            registration_date, status) SELECT event_id, user_id, organiserID, name, email, phone, 
                                                                            address, date_of_birth, skills_experience, additional_comments, registration_date, "Complete"
                                                                            FROM user_registration WHERE event_id = ?');
                                        $volunteerHistory->bind_param('i', $selectEventID);
                                        $volunteerHistory->execute();
                                        
                                        mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS = 0');

                                        $deleteEventVolunteers = $conn->prepare('DELETE FROM event_volunteers WHERE attendance_id IN
                                                                                (SELECT attendance_id FROM volunteer_attendance 
                                                                                WHERE status = ?)');
                                        $statusComplete = 'Complete';
                                        $deleteEventVolunteers->bind_param('s', $statusComplete);
                                        $deleteEventVolunteers->execute();
                                       
                                        $deleteEventCoordinations = $conn->prepare('DELETE ec FROM event_coordinations AS ec
                                                                                INNER JOIN volunteer_attendance AS va ON ec.event_id = va.event_id
                                                                                WHERE va.status = ?');
                                        $statusComplete = 'Complete';
                                        $deleteEventCoordinations->bind_param('s', $statusComplete);
                                        $deleteEventCoordinations->execute();
                                        
                                        $deleteVolunteerAttendance = $conn->prepare('DELETE FROM volunteer_attendance WHERE user_id IN
                                                                                (SELECT user_id FROM user_registration
                                                                                WHERE status = ?)');
                                        $statusComplete = 'Complete';
                                        $deleteVolunteerAttendance->bind_param('s', $statusComplete);
                                        $deleteVolunteerAttendance->execute();

                                        $deleteUserRegistration = $conn->prepare('DELETE FROM user_registration WHERE user_id IN
                                                                                (SELECT user_id FROM users
                                                                                WHERE user_status = ?)');
                                        $statusComplete = 'available';
                                        $deleteUserRegistration->bind_param('s', $statusComplete);
                                        $deleteUserRegistration->execute();
                                        

                                        mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS = 1');
                                      
                                        
                                
                                        echo '<p>Completed Event.</p>';
                                    }
                                ?>
                            </td>

                        </tr>
                    </tbody>
                </table>
          
        </div>

        
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const addBtn = document.getElementById("addBtn");
            const station_body = document.getElementById("station_body");
            const station_row = document.querySelector(".station-row");

            let stationCount = 1;

            addBtn.addEventListener("click", () => {
                stationCount++;

                const clonedRow = station_row.cloneNode(true);
                clonedRow.querySelector("input[name='stationName[]']").value = "";
                station_body.appendChild(clonedRow);
                deleteBtn(clonedRow);
            });
            
            function deleteBtn(row){
                const deleteBtn = row.querySelector(".deleteBtn");
                deleteBtn.addEventListener("click", () => {
                    row.remove();
                });
            }

            function removeUser(attendanceID) {
                if (confirm('Are you sure to remove the user from this station?')){
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'attendanceID';
                    input.value = attendanceID;

                    form.appendChild(input);

                    var actionInput = document.createElement('input');
                    actioninput.type = 'hidden';
                    actioninput.name = 'removeUser';
                    actioninput.value = '1';

                    form.appendChild(actionInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        });
    </script>
</body>
</html>
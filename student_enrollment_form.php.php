<?php
$host = "localhost";
$user = "root"; 
$pass = "";      
$dbname = "school_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$roll_no = $full_name = $class_name = $birth_date = $address = $enrollment_date = "";
$message = "";
$mode = "new"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["reset"])) {
        // Reset form
        header("Location: student_enrollment_form.php");
        exit();
    }

    $roll_no = trim($_POST["roll_no"]);
    $full_name = trim($_POST["full_name"]);
    $class_name = trim($_POST["class_name"]);
    $birth_date = trim($_POST["birth_date"]);
    $address = trim($_POST["address"]);
    $enrollment_date = trim($_POST["enrollment_date"]);

    if (isset($_POST["check"])) {
        
        $sql = "SELECT * FROM student_table WHERE roll_no='$roll_no'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $full_name = $row["full_name"];
            $class_name = $row["class_name"];
            $birth_date = $row["birth_date"];
            $address = $row["address"];
            $enrollment_date = $row["enrollment_date"];
            $mode = "update";
            $message = "Record found. You can update details.";
        } else {
            // New student
            $mode = "new";
            $message = "No record found. You can enter new details.";
        }
    }

    if (isset($_POST["save"])) {
        if ($roll_no && $full_name && $class_name && $birth_date && $address && $enrollment_date) {
            $sql = "INSERT INTO student_table VALUES ('$roll_no','$full_name','$class_name','$birth_date','$address','$enrollment_date')";
            if ($conn->query($sql) === TRUE) {
                $message = "New student saved successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        } else {
            $message = "All fields are required.";
        }
    }

    if (isset($_POST["update"])) {
        if ($roll_no && $full_name && $class_name && $birth_date && $address && $enrollment_date) {
            $sql = "UPDATE student_table SET 
                    full_name='$full_name',
                    class_name='$class_name',
                    birth_date='$birth_date',
                    address='$address',
                    enrollment_date='$enrollment_date'
                    WHERE roll_no='$roll_no'";
            if ($conn->query($sql) === TRUE) {
                $message = "Student updated successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        } else {
            $message = "All fields are required.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        input, button { margin: 5px; padding: 5px; }
        button:disabled { background: #ccc; }
        .msg { color: green; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Student Enrollment Form</h2>
    <form method="POST">
        <label>Roll No: 
            <input type="number" name="roll_no" value="<?php echo $roll_no; ?>" 
                   <?php echo ($mode=="update") ? "readonly" : ""; ?> required>
        </label><br>

        <label>Full Name: 
            <input type="text" name="full_name" value="<?php echo $full_name; ?>" 
                   <?php echo ($mode=="new" && !$message) ? "disabled" : ""; ?>>
        </label><br>

        <label>Class: 
            <input type="text" name="class_name" value="<?php echo $class_name; ?>" 
                   <?php echo ($mode=="new" && !$message) ? "disabled" : ""; ?>>
        </label><br>

        <label>Birth Date: 
            <input type="date" name="birth_date" value="<?php echo $birth_date; ?>" 
                   <?php echo ($mode=="new" && !$message) ? "disabled" : ""; ?>>
        </label><br>

        <label>Address: 
            <input type="text" name="address" value="<?php echo $address; ?>" 
                   <?php echo ($mode=="new" && !$message) ? "disabled" : ""; ?>>
        </label><br>

        <label>Enrollment Date: 
            <input type="date" name="enrollment_date" value="<?php echo $enrollment_date; ?>" 
                   <?php echo ($mode=="new" && !$message) ? "disabled" : ""; ?>>
        </label><br><br>

        <!-- Control Buttons -->
        <button type="submit" name="check">Check Roll No</button>
        <button type="submit" name="save" <?php echo ($mode=="new" && $message) ? "" : "disabled"; ?>>Save</button>
        <button type="submit" name="update" <?php echo ($mode=="update") ? "" : "disabled"; ?>>Update</button>
        <button type="submit" name="reset">Reset</button>
    </form>

    <div class="msg"><?php echo $message; ?></div>
</body>
</html>

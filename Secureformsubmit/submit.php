<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["pesan"];

    $file = $_FILES["file"];
    $fileName = $_FILES["file"]["name"];
    $fileTmpName = $_FILES["file"]["tmp_name"];
    $fileSize = $_FILES["file"]["size"];
    $fileError = $_FILES["file"]["error"];
    $fileType = $_FILES["file"]["type"];

    $fileExt = explode(".", $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array("jpg", "jpeg", "png", "pdf", "docx", "txt");

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid("", true) . "." . $fileActualExt;
                $fileDestination = "uploads/" . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);

                // Insert data into database
                $mysqli = new mysqli("localhost", "root", "", "kontak");
                
                // Check the connection
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                $sql = "INSERT INTO kontak (name, email, pesan, file) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($sql);

                // Check if the statement is prepared successfully
                if ($stmt === false) {
                    die("Error: " . $mysqli->error);
                }

                $stmt->bind_param("ssss", $name, $email, $message, $fileNameNew);
                $stmt->execute(); 

                echo "Success! Your file has been uploaded.";
            } else {
                echo "Error! Your file is too big.";
            }
        } else {
            echo "Error! There was a problem uploading your file.";
        }
    } else {
        echo "Error! Invalid file type.";
    }

    // Close the database connection
    $stmt->close();
    $mysqli->close();
}
?>

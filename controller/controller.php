<?php
// UserController.php

require_once 'model/model.php'; // Include the model file

class UserController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new UserModel($pdo);
    }

    // Function to handle user registration
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validate input (basic example)
            if (!empty($username) && !empty($email) && !empty($password)) {
                // Register the user using the model
                if ($this->userModel->register($username, $email, $password)) {
                    echo "Registration successful";
                    // Redirect or show a success message
                } else {
                    echo "Registration failed";
                    // Handle failure (e.g., show an error message)
                }
            } else {
                echo "Please fill all fields";
                // Handle validation error
            }
        } else {
            echo "Invalid request method";
            // Handle invalid request method
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (!empty($email) && !empty($password)) {
                // Sanitize input
                $email = htmlspecialchars($email);
                $password = htmlspecialchars($password);

                // Call model to validate user
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    // Successful login
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $user['id']; // Store user ID in session
                    header('Location: ?action=dashboard');
                    exit(); // Ensure no further code is executed
                } else {
                    echo "<script>
                            window.alert('Invalid email or password.');
                             window.location = '?action=showlogin';
                            </script>";
                    // Display user data for debugging
                }
            } else {
                echo "Please fill all fields";
            }
        } else {
            echo "Invalid request method";
        }
    }

    public function logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If the session was propagated using a cookie, delete that cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session.
        session_destroy();

        // Redirect to the login page or any other page
        echo "<script>
                alert('Logged out successfully');
                window.location.href = 'index.php?action=showlogin';
              </script>";
        exit;
    }

    public function showlogin()
    {
        include("view/login.php");
    }

    public function showregister()
    {
        include("view/register.php");
    }

    public function showdashboard()
    {
        if (!isset($_SESSION['id'])) {
            echo "You must be logged in to view this page.";
            exit();
        }
    
        $userId = $_SESSION['id'];
    
        // Pagination settings
        $limit = 3; // Number of records per page
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
    
        // Sorting settings
        $currentSortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
        $currentSortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';
        $nextSortOrder = $currentSortOrder === 'DESC' ? 'ASC' : 'DESC';
    
        // Get user information
        $user = $this->userModel->getUserById($userId);
        $username = $user['username'];
    
        // Get total number of records for pagination
        $totalRecords = $this->userModel->getTotalAttendanceLogCount($userId);
    
        // Get attendance log records for the current page
        $attendanceLog = $this->userModel->getAttendanceLog($userId, $limit, $offset, $currentSortBy, $currentSortOrder);
    
        // Pass records and pagination info to the view
        include 'view/dashboard.php';
    }
    



    public function checkIn($userId)
    {
        $timezone = new DateTimeZone('Asia/Kolkata');
        $date = date('Y-m-d');
        $checkInTime = (new DateTime('now', $timezone))->format('Y-m-d H:i:s');
        $this->userModel->recordCheckIn($userId, $date, $checkInTime);
    }

    public function checkOut($userId)
    {
        $timezone = new DateTimeZone('Asia/Kolkata');
        $date = date('Y-m-d');
        $checkOutTime = (new DateTime('now', $timezone))->format('Y-m-d H:i:s');

        // Update check-out time in attendance table
        $this->userModel->recordCheckOut($userId, $date, $checkOutTime);

        // Retrieve check-in and check-out times
        $attendanceRecord = $this->userModel->getUserAttendance($userId, $date);
        $hoursWorked = 0;

        if ($attendanceRecord) {
            $checkInTime = $attendanceRecord['check_in_time'];
            $checkOutTime = $attendanceRecord['check_out_time'];

            if ($checkInTime && $checkOutTime) {
                $checkIn = new DateTime($checkInTime, $timezone);
                $checkOut = new DateTime($checkOutTime, $timezone);
                $interval = $checkIn->diff($checkOut);
                $hoursWorked = $interval->h + ($interval->i / 60);
            }
        }

        // Determine status
        $status = $this->determineStatus($hoursWorked);

        // Update or insert into attendance log
        $this->userModel->updateAttendanceLog($userId, $date, $attendanceRecord['check_in_time'], $checkOutTime, $hoursWorked, $status);
    }

    // Update or create the attendance log
    private function updateAttendanceLog($userId, $date)
    {
        // Get the attendance record for the day
        $attendance = $this->userModel->getUserAttendance($userId, $date);

        if ($attendance) {
            $firstCheckIn = $attendance['check_in_time'];
            $lastCheckOut = $attendance['check_out_time'];

            if ($lastCheckOut) {
                $hoursWorked = $this->calculateHoursWorked($firstCheckIn, $lastCheckOut);
                $status = $this->determineStatus($hoursWorked);

                // Update hours worked in attendance table
                $this->updateHoursWorkedInAttendance($userId, $date, $hoursWorked);

                // Update the attendance log
                $this->userModel->updateAttendanceLog($userId, $date, $firstCheckIn, $lastCheckOut, $hoursWorked, $status);
            }
        }
    }

    // Update hours worked in the attendance table
    public function updateHoursWorkedInAttendance($userId, $date, $hoursWorked)
    {
        $sql = "UPDATE attendance SET hours_worked = :hours_worked WHERE user_id = :user_id AND date = :date";
        $stmt = $this->userModel->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'date' => $date,
            'hours_worked' => $hoursWorked
        ]);
    }

    // Calculate hours worked based on check-in and check-out times
    private function calculateHoursWorked($checkInTime, $checkOutTime)
    {
        $checkIn = new DateTime($checkInTime);
        $checkOut = new DateTime($checkOutTime);
        $interval = $checkIn->diff($checkOut);
        return $interval->h + ($interval->i / 60);
    }

    // Determine the attendance status based on hours worked
    private function determineStatus($hoursWorked)
    {
        if ($hoursWorked >= 8) {
            return 'Full Day';
        } elseif ($hoursWorked >= 4) {
            return 'Half Day';
        } else {
            return 'Absent';
        }
    }

    // Show attendance log for a user and date
    // public function showAttendanceLog($userId, $date)
    // {
    //     return $this->userModel->getAttendanceLog($userId, $date);
    // }
    public function searchDate()
    {
        // Check if the request is an AJAX POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
            $date = $_POST['date'];
            $userId = $_SESSION['id']; // Assuming user ID is stored in session
    
            // Fetch attendance records for the given date
            $attendanceLog = $this->userModel->getAttendanceByDate($userId, $date);
    
            // Check if any records were found
            if (empty($attendanceLog)) {
                echo "<tr>
                <td colspan='6' class='text-center'>No records found</td>
              </tr>";
            } else {
                // Generate HTML for table rows
                foreach ($attendanceLog as $log) {
                    echo "<tr>
                    <td>" . htmlspecialchars($log['date']) . "</td>
                    <td>" . htmlspecialchars($log['first_check_in']) . "</td>
                    <td>" . htmlspecialchars($log['last_check_out']) . "</td>
                    <td>" . htmlspecialchars($log['total_hours']) . "</td>
                    <td>" . htmlspecialchars($log['status']) . "</td>
                    <td>
                        <!-- Update button -->
                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#updateModal'
                            onclick='openModal(" . htmlspecialchars($log['id']) . ")'>
                            Update
                        </button>
                    </td>
                    </tr>";
                }
            }
    
            exit; // End the script to prevent any additional output
        }
    }
    public function paginate($page = 1)
{
    $limit = 10; // Adjust as needed
    $offset = ($page - 1) * $limit;
    $userId = $_SESSION['id']; // Assuming user ID is stored in session

    // Fetch paginated attendance records
    $attendanceLog = $this->userModel->getPaginatedAttendance($userId, $limit, $offset);
    $totalRecords = $this->userModel->countTotalAttendance($userId); // Total number of records

    // Generate HTML for table rows
    $response = '';
    if (empty($attendanceLog)) {
        $response = "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
    } else {
        foreach ($attendanceLog as $log) {
            $response .= "<tr>
                <td>" . htmlspecialchars($log['date']) . "</td>
                <td>" . htmlspecialchars($log['first_check_in']) . "</td>
                <td>" . htmlspecialchars($log['last_check_out']) . "</td>
                <td>" . htmlspecialchars($log['total_hours']) . "</td>
                <td>" . htmlspecialchars($log['status']) . "</td>
                <td>
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#updateModal'
                        onclick='openModal(" . htmlspecialchars($log['id']) . ")'>
                        Update
                    </button>
                </td>
            </tr>";
        }
    }

    // Generate HTML for pagination
    $paginationHtml = $this->generatePagination($page, $totalRecords, $limit);

    echo json_encode([
        'tableRows' => $response,
        'pagination' => $paginationHtml
    ]);
    exit;
}
private function generatePagination($currentPage, $totalRecords, $limit)
{
    $totalPages = ceil($totalRecords / $limit);
    $paginationHtml = '<ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        $paginationHtml .= '<li class="page-item">
            <button class="page-link" data-page="' . ($currentPage - 1) . '" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </button>
        </li>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        $paginationHtml .= '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">
            <button class="page-link" data-page="' . $i . '">' . $i . '</button>
        </li>';
    }

    if ($currentPage < $totalPages) {
        $paginationHtml .= '<li class="page-item">
            <button class="page-link" data-page="' . ($currentPage + 1) . '" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </button>
        </li>';
    }

    $paginationHtml .= '</ul>';

    return $paginationHtml;
}

    public function sort()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sort_by'], $_POST['sort_order'])) {
        $sortBy = $_POST['sort_by'];
        $sortOrder = $_POST['sort_order']; // Corrected to fetch from POST
        $userId = $_SESSION['id']; // Assuming user ID is stored in session

        // Fetch sorted attendance records
        $attendanceLog = $this->userModel->getSortedAttendance($userId, $sortBy, $sortOrder);

        if (!empty($attendanceLog)) {
            foreach ($attendanceLog as $log) {
                echo "<tr>
                    <td>" . htmlspecialchars($log['date']) . "</td>
                    <td>" . htmlspecialchars($log['first_check_in']) . "</td>
                    <td>" . htmlspecialchars($log['last_check_out']) . "</td>
                    <td>" . htmlspecialchars($log['total_hours']) . "</td>
                    <td>" . htmlspecialchars($log['status']) . "</td>
                    <td>
                        <!-- Update button -->
                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#updateModal'
                            onclick='openModal(" . htmlspecialchars($log['id']) . ")'>
                            Update
                        </button>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>"; // Corrected colspan to 6
        }

        exit; // Stop script execution
    }
}


    public function getAttendanceData()
    {
        $id = $_GET['id'];
        $record = $this->userModel->getAttendanceById($id);
        echo json_encode($record);
    }

   
    // public function update($id)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $first_check_in = $_POST['first_check_in'];
    //         $last_check_out = $_POST['last_check_out'];
    //         $total_hours = $_POST['total_hours'];

    //         $this->userModel->updateAttendance($id, $first_check_in, $last_check_out, $total_hours);
    //         header('Location: index.php?action=dashboard');
    //     }
    // }
    public function download_csv()
    {
        // Fetch all attendance records (or filter as needed)
        $userId = $_SESSION['id']; // Assuming user ID is stored in session
        $attendanceRecords = $this->userModel->getAllAttendance($userId);

        // Set headers to force download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="attendance_records.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV headers
        fputcsv($output, ['Date', 'first_check_in', 'last_check_out', 'total_hours', 'Status']);

        // Add records to CSV
        foreach ($attendanceRecords as $record) {
            fputcsv($output, [
                $record['date'],
                $record['first_check_in'],
                $record['last_check_out'],
                $record['total_hours'],
                $record['status']
            ]);
        }

        // Close output stream
        fclose($output);
        exit; // Ensure no further output is sent
    }
    public function updateAttendanceData()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $attendanceId = $_POST['id'];
        $firstCheckIn = $_POST['first_check_in'];
        $lastCheckOut = $_POST['last_check_out'];
        $totalHours = $_POST['total_hours'];
        $status = $_POST['status'];

        // Update attendance data in the database
        $this->userModel->updateAttendance($attendanceId, $firstCheckIn, $lastCheckOut, $totalHours,$status);

        // Redirect back to the dashboard or return a success response
        header('Location: index.php?action=dashboard');
        exit();
    }
}
}
?>
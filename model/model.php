<?php
// UserModel.php
include_once("config\config.php");
class UserModel
{
    private $conn;

    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    // Function to register a new user
    public function register($username, $email, $passwordHash)
    {
        // Hash the password
        $passwordHash = password_hash($passwordHash, PASSWORD_BCRYPT);

        // Prepare and bind
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);

        // Execute the query
        return $stmt->execute();
    }
    public function login($email, $passwordHash)
    {
        $sql = "SELECT id, email, password_hash FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug output
        echo '<pre>';
        var_dump($user);
        echo '</pre>';

        if ($user && password_verify($passwordHash, $user['password_hash'])) {
            return $user; // Return user data
        }

        return false; // Invalid email or password
    }



    // Record check-in time
    public function recordCheckIn($userId, $date, $checkInTime)
    {
        $sql = "INSERT INTO attendance (user_id, date, check_in_time) VALUES (:user_id, :date, :check_in_time)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'date' => $date,
            'check_in_time' => $checkInTime
        ]);
    }

    public function recordCheckOut($userId, $date, $checkOutTime)
    {
        $sql = "UPDATE attendance SET check_out_time = :check_out_time WHERE user_id = :user_id AND date = :date";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'date' => $date,
            'check_out_time' => $checkOutTime
        ]);
    }

    public function updateAttendanceLog($userId, $date, $firstCheckIn, $lastCheckOut, $totalHours, $status)
    {
        $sql = "INSERT INTO attendance_log (user_id, date, first_check_in, last_check_out, total_hours, status)
                VALUES (:user_id, :date, :first_check_in, :last_check_out, :total_hours, :status)
                ON DUPLICATE KEY UPDATE
                first_check_in = VALUES(first_check_in),
                last_check_out = VALUES(last_check_out),
                total_hours = VALUES(total_hours),
                status = VALUES(status)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'date' => $date,
            'first_check_in' => $firstCheckIn,
            'last_check_out' => $lastCheckOut,
            'total_hours' => $totalHours,
            'status' => $status
        ]);
    }

    public function getUserAttendance($userId, $date)
    {
        $sql = "SELECT * FROM attendance WHERE user_id = :user_id AND date = :date";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'date' => $date]);
        return $stmt->fetch();
    }

    // public function getAttendanceLog($userId, $date, $limit, $offset, $sortBy = 'date', $sortOrder = 'ASC')
    // {
    //     $sql = "SELECT * FROM attendance_log 
    //             WHERE user_id = :user_id AND date = :date
    //             ORDER BY $sortBy $sortOrder
    //             LIMIT :limit OFFSET :offset";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bindParam(':user_id', $userId);
    //     $stmt->bindParam(':date', $date);
    //     $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    //     $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getAttendanceLog($userId, $limit, $offset, $sortBy, $sortOrder)
    {
        $sql = "SELECT * FROM attendance_log WHERE user_id = :user_id 
            ORDER BY $sortBy $sortOrder 
            LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($userId)
    {
        $sql = "SELECT username FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function getTotalAttendanceLogCount($userId, $date)
    // {
    //     $sql = "SELECT COUNT(*) as count FROM attendance_log WHERE user_id = :user_id AND date = :date";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bindParam(':user_id', $userId);
    //     $stmt->bindParam(':date', $date);
    //     $stmt->execute();
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $result['count'];
    // }
    public function getTotalAttendanceLogCount($userId)
    {
        $sql = "SELECT COUNT(*) FROM attendance_log WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

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
    // public function getAttendanceByDate($userId, $date) {
    //     $stmt = $this->conn->prepare("SELECT * FROM attendance_log WHERE user_id = ? AND date = ?");
    //     $stmt->execute([$userId, $date]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getPaginatedAttendance($userId, $limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT * FROM attendance_log WHERE user_id = ? LIMIT ? OFFSET ?");
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countTotalAttendance($userId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM attendance_log WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }



    public function getAttendanceByDate($userId, $date)
    {
        $stmt = $this->conn->prepare("SELECT * FROM attendance_log WHERE user_id = ? AND date = ?");
        $stmt->execute([$userId, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function getSortedAttendance($userId, $sortBy, $sortOrder)
    // {
    //     $sql = "SELECT * FROM attendance_log WHERE user_id = :user_id ORDER BY {$sortBy} {$sortOrder}";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getSortedAttendance($userId, $sortBy, $sortOrder)
    {
        $stmt = $this->conn->prepare("SELECT * FROM attendance_log WHERE user_id = ? ORDER BY $sortBy $sortOrder");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAttendanceById($id)
    {
        // Fetch the attendance record by ID
        $query = "SELECT * FROM attendance_log WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function updateAttendance($id, $firstCheckIn, $lastCheckOut, $totalHours, $status)
    {
        $sql = "UPDATE attendance_log SET first_check_in = :first_check_in, last_check_out = :last_check_out, total_hours = :total_hours, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_check_in', $firstCheckIn);
        $stmt->bindParam(':last_check_out', $lastCheckOut);
        $stmt->bindParam(':total_hours', $totalHours);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }
    // public function updateAttendance($id,  $first_check_in, $last_check_out, $total_hours) {
    //     // Update the attendance record
    //     $query = "UPDATE attendance_log SET first_check_in = ?, last_check_out = ?, total_hours = ? WHERE id = ?";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([ $first_check_in, $last_check_out, $total_hours, $id]);
    // }

    public function getAllAttendance($userId)
    {
        $query = "SELECT * FROM attendance_log WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

}
?>
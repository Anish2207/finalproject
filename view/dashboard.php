<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            color: #333333;
        }

        .navbar {
            background-color: #80bab1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0.5rem 1.5rem;
        }

        .navbar-brand,
        .nav-link {
            color: #1f2121;
        }

        .navbar-nav .nav-link:hover {
            color: #0056b3;
        }

        .container {
            margin-top: 40px;
        }

        .table thead th {
            background-color: #48877d;
            color: #ffffff;
            text-align: center;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background-color: #e0e0e0;
            transition: background-color 0.2s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
        }

        .pagination .page-link {
            color: #007bff;
            transition: background-color 0.2s ease;
        }

        .pagination .page-link:hover {
            background-color: #0056b3;
            color: #ffffff;
        }

        .card {
            background-color: #ffffff;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
        }

        .card-header {
            background-color: #007bff;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-weight: 500;
        }

        .btn {
            background-color: #007bff;
            color: #ffffff;
            transition: background-color 0.3s ease;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
            color: #ffffff;
        }

        h3 {
            margin-bottom: 20px;
            font-weight: 500;
            color: #333333;
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <h3 class="text-center text-md-left">Welcome <?php echo htmlspecialchars($username); ?></h3>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="form-group text-center">
                        <input type="date" id="searchDate" class="form-control" placeholder="Search by Date" required>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="index.php?action=download_csv" class="nav-link"><i class="fas fa-download"></i>Download</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=checkIn"><i class="fas fa-sign-in-alt"></i> Check In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=checkOut"><i class="fas fa-sign-out-alt"></i> Check Out</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?action=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h3>My Attendance Records</h3>

        <div class="card-body">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>
                            <a href="#" class="sort" data-sort-by="date"
                                data-sort-order="<?php echo $nextSortOrder; ?>">Date</a>
                        </th>
                        <th>First Check-In</th>
                        <th>Last Check-Out</th>
                        <th>Total Hours</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable">
                    <?php if (empty($attendanceLog)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No records found</td>
                        </tr>
                    <?php else: ?>
                        <?php $tableRows = ''; ?>
                        <?php foreach ($attendanceLog as $log): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($log['date']); ?></td>
                                <td><?php echo htmlspecialchars($log['first_check_in']); ?></td>
                                <td><?php echo htmlspecialchars($log['last_check_out']); ?></td>
                                <td><?php echo htmlspecialchars($log['total_hours']); ?></td>
                                <td><?php echo htmlspecialchars($log['status']); ?></td>
                                <td>
                                    <!-- Update button -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal"
                                        onclick="openModal(<?= $log['id'] ?>)">
                                        Update
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal for Updating Attendance Data -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Attendance Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="first_check_in">First Check-In</label>
                            <input type="datetime-local" class="form-control" id="first_check_in" name="first_check_in"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="last_check_out">Last Check-Out</label>
                            <input type="datetime-local" class="form-control" id="last_check_out" name="last_check_out"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="total_hours">Total Hours</label>
                            <input type="text" class="form-control" id="total_hours" name="total_hours" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="Full Day">Full Day</option>
                                <option value="Half Day">Half Day</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Pagination Controls -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <button class="page-link" data-page="<?php echo $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= ceil($totalRecords / $limit); $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <button class="page-link" data-page="<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </button>
                </li>
            <?php endfor; ?>
            <?php if ($page < ceil($totalRecords / $limit)): ?>
                <li class="page-item">
                    <button class="page-link" data-page="<?php echo $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle the date search
            $('#searchDate').on('change', function () {
                var date = $(this).val();
                $.ajax({
                    url: 'index.php?action=filterdate',
                    type: 'POST',
                    data: { date: date },
                    success: function (response) {
                        $('tbody#attendanceTable').html(response);
                    },
                    error: function () {
                        alert('Error fetching records.');
                    }
                });
            });

            // Handle sorting
            $('.sort').on('click', function (e) {
                e.preventDefault();

                var sortBy = $(this).data('sort-by');
                var sortOrder = $(this).data('sort-order');

                $.ajax({
                    url: 'index.php?action=sort',
                    type: 'POST',
                    data: { sort_by: sortBy, sort_order: sortOrder },
                    success: function (response) {
                        $('tbody#attendanceTable').html(response);
                    },
                    error: function () {
                        alert('Error fetching sorted records.');
                    }
                });
            });
            ;
        });
        function openModal(id) {
            // Use AJAX to fetch the data for the selected record
            $.ajax({
                url: 'index.php?action=getAttendanceData',
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    var data = JSON.parse(response);  // Parse the JSON response
                    // Populate the form fields 
                    $('#id').val(data.id);
                    $('#first_check_in').val(data.first_check_in);
                    $('#last_check_out').val(data.last_check_out);
                    $('#total_hours').val(data.total_hours);
                    $('#status').val(data.status);
                    // Show the modal
                    $('#updateModal').modal('show');
                },
                error: function () {
                    alert('Error fetching attendance data.');
                }
            });
        }

        // Separate function to handle form submission
        $('#updateForm').submit(function (event) {
            event.preventDefault();  // Prevent default form submission
            $.ajax({
                url: 'index.php?action=update',
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    $('#updateModal').modal('hide');  // Hide the modal
                    location.reload();  // Reload the page to reflect changes
                },
                error: function () {
                    alert('Error updating attendance data.');
                }
            });
        });
        $(document).ready(function () {
            // Handle pagination button clicks
            $('.pagination').on('click', '.page-link', function () {
                var page = $(this).data('page');

                $.ajax({
                    url: 'index.php?action=dashboard&page=' + page,
                    type: 'GET',
                    success: function (response) {
                        // Update table content with the response
                        $('#attendanceTable').html($(response).find('#attendanceTable').html());

                        // Update pagination links
                        $('.pagination').html($(response).find('.pagination').html());
                    },
                    error: function () {
                        alert('Error fetching records.');
                    }
                });
            });
        });
    </script>
</body>

</html>
<?php
include 'dash_button.php';
include 'db_connect.php';
$id = $_GET['id'];
$sql = "SELECT member_id, CONCAT(fname, ' ', mname, ' ', lname) AS fullname";

if ($_GET['version'] == 1) {
    $sql .= ", birth_date, member_email, member_contact, address, gender";
} elseif ($_GET['version'] == 2) {
    $sql .= ", start_date, end_date, plan, package, trainer, status";
}
$sql .= " FROM members WHERE member_id=$id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <?php if ($_GET['version'] == 1): ?>
            <h3 class="mb-3">MEMBER DETAILS</h3>
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Membership ID</th>
                    <td><?php echo $row['member_id']; ?></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $row['fullname']; ?></td>
                </tr>
                <tr>
                    <th>Birthday</th>
                    <td><?php echo $row['birth_date']; ?></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><?php echo $row['member_email']; ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo $row['member_contact']; ?></td>
                </tr>
                <tr>
                    <th>Residential Address</th>
                    <td><?php echo $row['address']; ?></td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td><?php echo $row['gender']; ?></td>
                </tr>
            </table>
            <button class="btn btn-primary" onclick="window.location.href='members.php' "  style="background-color: #1e2126; border-color: #1e2126;">Back to Members List</button>
        <?php elseif ($_GET['version'] == 2): ?>
            <h3 class="mb-3">MEMBERSHIP VALIDITY DETAILS</h3>
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Membership ID</th>
                    <td><?php echo $row['member_id']; ?></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $row['fullname']; ?></td>
                </tr>
                <tr>
                    <th>Start of Membership</th>
                    <td><?php echo $row['start_date']; ?></td>
                </tr>
                <tr>
                    <th>End of Membership</th>
                    <td><?php echo $row['end_date']; ?></td>
                </tr>
                <tr>
                    <th>Plan</th>
                    <td><?php echo $row['plan']; ?></td>
                </tr>
                <tr>
                    <th>Package</th>
                    <td><?php echo $row['package']; ?></td>
                </tr>
                <tr>
                    <th>Trainer</th>
                    <td><?php echo !empty($row['trainer']) ? $row['trainer'] : 'None'; ?></td>
                </tr>
                <tr>
                    <th>Membership Status</th>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            </table>
            <button s class="btn btn-primary" onclick="window.location.href='membership.php'" style="background-color: #1e2126; border-color: #1e2126;">Back to Membership List</button>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
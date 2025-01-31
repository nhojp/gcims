<?php
ob_start();

include "head.php";
include "sidebar.php";
include "conn.php";

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to the login page
    header("Location: index.php");
    exit;
}

$sql_personnel = "SELECT 'teacher' as type, id, first_name, last_name, position FROM teachers";

$result_personnel = $conn->query($sql_personnel);
?>
<div id="main">

    <?php include "header.php"; ?>

    <div class="container-fluid mb-5">
        <div class="container-fluid bg-white mt-2 rounded-lg pb-2 border">
            <div class="row pt-3">
                <div class="col-md-6">
                    <div class="container-fluid p-2">
                        <h3><strong>Complain a School Personnel</strong></h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <input class="form-control" type="text" id="searchInput" placeholder="Search a name or position...">
                </div>
            </div>
            <table class="table table-hover mt-4 border">
                <thead class="thead-custom">
                    <tr>
                        <th style="width:45%;">Name</th>
                        <th style="width:45%;">Position</th>
                        <th style="width:10%;">Select</th>
                    </tr>
                </thead>
                <tbody id="personnelTable">
                    <?php if ($result_personnel->num_rows > 0) : ?>
                        <?php while ($person = $result_personnel->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo ucwords($person['first_name']) . ' ' . ucwords($person['last_name']); ?></td>
                                <td><?php echo ucwords($person['position']); ?></td>
                                <td>
                                    <form action="admin-p-2.php" method="post">
                                        <input type="hidden" name="person_id" value="<?php echo $person['id']; ?>">
                                        <input type="hidden" name="person_type" value="<?php echo $person['type']; ?>">
                                        <button type="submit" class="btn btn-success btn-block">Next</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center">No personnel found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var input = document.getElementById('searchInput').value.toLowerCase();
            var rows = document.querySelectorAll('#personnelTable tr');

            rows.forEach(function(row) {
                var name = row.cells[0].innerText.toLowerCase();
                var position = row.cells[1].innerText.toLowerCase();

                if (name.includes(input) || position.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
<?php
include "toast.php";
include "footer.php";
?>
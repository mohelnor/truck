<?php
require_once('Connections/travelo.php');

// Start the session
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: /travelo/');
}

require_once('sidenav.php');
?>
<div class="p-0 m-0">
<a class="btn btn-secondary" href="/travelo/company/report.php">الشركات</a>
<a class="btn btn-secondary" href="/travelo/seats/report.php">المقاعد</a>
</div>
<div class="row p-2 gap-2">
   
    <div class="col col-md-3 card p-0">
        <div class="card-body text-center text-muted">
            <i class="fas mx-1 fa-users fa-fw me-3 fa-5x"></i>
            <span class="badge rounded-pill badge-notification bg-danger" style="font-size:24px;">
            8
            </span>
        </div>
        <a href="/travelo/users/report.php" class="list-group-item list-group-item-action py-2 ripple card-footer text-center">
            <span>المستخدمين</span>
        </a>
    </div>
</div>
</div>
</main>
<!--Main layout-->
<!-- End your project here-->
</body>

</html>

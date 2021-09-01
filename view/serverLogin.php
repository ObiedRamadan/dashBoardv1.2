<?php
include '../shardFile/header.php';
include '../doc/function.php';
session_start();
session_unset();
?>
<!-- form to submit connection data -->
<div class="container">
    <div class="row justify-content-center align-items-center my-md-5">
        <form action="../back/systemController.php" method="post" class="row bdr py-5">
            <div class="col-12 text-center my-3">
                <h2 class="sub-heading">Connect To Server</h2>
            </div>
            <div class="col-md-6 form-group">
                <input type="text" name="dbType" class="form-control" placeholder="DatabaseType: mysql">
            </div>
            <div class="col-md-6 form-group">
                <input type="text" name="host" class="form-control" placeholder="Host: localhost:3306">
            </div>
            <div class="col-md-6 form-group">
                <input type="text" name="dbName" class="form-control" placeholder="Database Name: optional">
            </div>
            <div class="col-md-6 form-group">
                <input type="text" name="userName" class="form-control" placeholder="User Name: root" />
            </div>
            <div class="col-md-6 form-group">
                <input type="text" name="password" class="form-control" placeholder="Password:" />
            </div>
            <div class="col-md-12 form-group text-center my-2">
                <button class="btn btn-outline-primary" name="serverCont">connection</button>
            </div>
        </form>
        <?php include '../shardFile/error.php' ?>
    </div>
</div>
<?php include '../shardFile/footer.php' ?>
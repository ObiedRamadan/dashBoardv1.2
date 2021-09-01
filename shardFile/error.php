<?php if (isset($_SESSION['error'])) { ?>
    <div class="container my-2">
        <?php foreach ($_SESSION['error'] as $key => $value) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $value ?>
            </div>
        <?php
        }
        unset($_SESSION['error']);
        ?>
    </div>
<?php } ?>
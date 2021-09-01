<?php include '../shardFile/header.php' ?>
<?php include '../back/systemFlow.php' ?>

<section id="header">
    <h1 class='heading py-5'>Welcome To Dashboard</h1>
    <div class="container-fluid">
        <div class="row">
            <!-- database drop down menu -->
            <div class="col-6">
                <div class="">
                    <form action="../back/systemController.php" method="POST" class="row">
                        <div class="form-group col-6">
                            <select name="databaseName" id="" class="form-control">
                                <option value="none">Select Database -----</option>
                                <?php for ($i = 0; $i < count($databases); $i++) { ?>
                                    <option value="<?php echo  $databases[$i][0] ?>"><?php echo  $databases[$i][0] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group ">
                            <button name="setDb" class="btn btn-click"> Go </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- log out from server -->
            <div class="col-6">
                <div>
                    <form action="../back//systemController.php" method="POST">
                        <button type="submit" name="logout" class="btn btn-click float-right">Log out</button>
                        <div class="clear-fix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../shardFile/error.php' ?>
</section>

<section id="Table">
    <div class="container-fluid my-3">
        <div class="row">
            <!-- Database Tables -->
            <div class="col-md-2">
                <div class='bdr'>
                    <h2 class="sub-heading py-2"><?php echo $_SESSION['connInfo']['dbName'] ?></h2>
                    <div>
                        <?php for ($i = 0; $i < count($table); $i++) { ?>
                            <form action="../back/systemController.php" method="POST" class="my-1">
                                <input type="hidden" name="tableName" value="<?php echo $table[$i][0] ?>">
                                <button type="submit" name="setTableName" class="tableName w-100"><?php echo $table[$i][0] ?></button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- records table -->
            <!-- the table will display if there are table colums -->
            <div class="col-md-10">
                <div class="bdr  px-0">
                    <?php if ($tableColums) { ?>
                        <!-- Add Record  -->
                        <div class='addBox'>
                            <h5 class="sub-heading">Add New Record</h5>
                            <form method="POST" action="../back/systemController.php" class="d-flex flex-wrap">
                                <?php for ($i = 1; $i < count($tableColums); $i++) {
                                    if ($tableColums[$i]['Null'] == 'NO' && $tableColums[$i]['Default'] == null) { ?>
                                        <input required type="text" name="<?php echo $tableColums[$i]['Field'] ?>" class="addCell form-control" placeholder="<?php echo $tableColums[$i]['Field'] . ' (' . $tableColums[$i]['Type'] . ' )' ?>">
                                    <?php } else { ?>
                                        <input type="text" name="<?php echo $tableColums[$i]['Field'] ?>" class="addCell form-control" placeholder="<?php echo $tableColums[$i]['Field'] . ' (' . $tableColums[$i]['Type'] . ' )' ?>">
                                    <?php } ?>
                                <?php } ?>
                                <button type="submit" class="btn addBtn form-control" name="add">Add</button>
                            </form>
                        </div>

                        <!-- Table Record -->
                        <table class="table table-striped">
                            <thead class='thead-dark'>
                                <tr>
                                    <?php
                                    for ($i = 0; $i < count($tableColums); $i++) { ?>
                                        <th class="tCell"><?php echo $tableColums[$i]['Field'] ?></th>
                                    <?php } ?>

                                    <th class="tCell">Operator</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($tableRecord as $key => $val) {
                                    if ($key >= ($pag * 10) && $key < (($pag * 10) + 10)) {
                                        // echo 'key: '.$key.'start: '. ($pag * 10).'end: '.(($pag * 10) + 10).'<br>';
                                ?>
                                        <!-- normal row -->
                                        <tr>
                                            <?php for ($i = 0; $i < count($val) / 2; $i++) { ?>
                                                <td class="tCell"><?php echo $val[$i] ?></td>
                                            <?php } ?>

                                            <td class="d-flex justify-content-start tCell">
                                                <button type="button" class='btn btn-warning updata'>updata</button>
                                                <form action="../back/systemController.php" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $val[0] ?>">
                                                    <button class='btn btn-danger ml-1' name="delete">delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <!--updata  -->
                                        <tr class="updataForm bg-light">
                                            <form method="POST" action="../back/systemController.php" class="d-flex flex-wrap">
                                                <td class="tCell">
                                                    <div class=" form-control" name><?php echo $val[0] ?></div>
                                                    <input type="hidden" name="id" value="<?php echo $val[0] ?>">
                                                </td>
                                                <?php for ($i = 1; $i < count($val) / 2; $i++) {
                                                    if ($tableColums[$i]['Null'] == 'NO' && $tableColums[$i]['Default'] == null) { ?>
                                                        <td class="tCell"><input required type="text" name="<?php echo $tableColums[$i]['Field'] ?>" class=" form-control" value="<?php echo $val[$i] ?>"></td>
                                                    <?php } else { ?>
                                                        <td class="tCell"><input type="text" name="<?php echo $tableColums[$i]['Field'] ?>" class=" form-control" value="<?php echo $val[$i] ?>"></td>
                                                    <?php } ?>
                                                <?php } ?>

                                                <td class="tCell">
                                                    <button type="submit" class="btn btn-outline-success" name="saveUpdata">Save</button>
                                                    <button type="button" class="btn btn-outline-danger cansal">cansal</button>
                                                </td>
                                            </form>
                                        </tr>
                                    <?php  } ?>
                                <?php  } ?>
                            </tbody>
                        </table>

                        <!-- pagination -->
                        <nav>
                            <ul class="pagination pagination-md d-flex flex-wrap">
                                <?php for ($i = 0; $i <= $totalPag; $i++) { ?>
                                    <form action="../back/systemController.php" method="post">
                                        <input type="hidden" name="pag" value="<?php echo $i ?>">
                                        <li class="page-item"><button class="page-link" name="setPag"><?php echo $i + 1 ?></button></li>
                                    </form>
                                <?php } ?>
                            </ul>

                        </nav>

                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../shardFile/footer.php' ?>
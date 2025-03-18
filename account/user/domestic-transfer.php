<?php
$pageName = "Domestic Transfer";
include_once("layouts/header.php");
require_once("./userPinfunction.php");
//require_once("../include/config.php");
//require_once("../include/loginFunction.php");
//require_once("../include/userFunction.php");
//require_once('../include/userClass.php');






?>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-8 offset-md-2">
                <div class="card component-card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if ($acct_stat === 'active') {
                                    ?>
                                        <h3 class="text-center">Domestic Transfer</h3>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Amount</label>
                                                        <div class="input-group ">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><svg fill="#333333" height="18px" width="18px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        viewBox="0 0 310.75 310.75" xml:space="preserve">
                                                                        <path d="M183.815,265.726c-32.444,0-60.868-21.837-76.306-54.325h102.101v-45.023H95.643c-0.284-3.642-0.437-7.29-0.437-11.016
	c0-3.691,0.152-7.384,0.437-10.977h113.969V99.353H107.51c15.438-32.485,43.861-54.315,76.306-54.315
	c31.01,0,60.21,20.759,76.2,54.152l40.626-19.418C277.091,30.554,232.329,0,183.815,0c-36.47,0-70.51,16.665-95.851,46.966
	C75.219,62.209,65.481,79.995,59.079,99.353H10.108v45.031h40.39c-0.217,3.617-0.329,7.311-0.329,10.977
	c0,3.704,0.112,7.351,0.329,11.016h-40.39V211.4h48.971c6.402,19.356,16.14,37.122,28.886,52.351
	c25.341,30.303,59.381,46.999,95.851,46.999c48.515,0,93.275-30.55,116.826-79.767l-40.626-19.454
	C244.025,244.965,214.825,265.726,183.815,265.726z" />
                                                                    </svg></span>
                                                            </div>
                                                            <input type="number" class="form-control" name="amount" value="<?= $_POST['amount'] ?>" placeholder="Amount" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Beneficiary Account Name</label>
                                                        <div class="input-group ">
                                                            <input type="text" class="form-control" name="acct_name" placeholder="Beneficiary Account Name" aria-label="notification" aria-describedby="basic-addon1" value="<?= $_POST['acct_name'] ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Bank Name</label>
                                                        <div class="input-group ">
                                                            <input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value="<?= $_POST['bank_name'] ?>" aria-label="notification" aria-describedby="basic-addon1" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Beneficiary Account No</label>
                                                        <div class="input-group ">
                                                            <input type="number" class="form-control" name="acct_number" placeholder="Beneficiary Account Name" aria-label="notification" aria-describedby="basic-addon1" value="<?= $_POST['acct_number'] ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-md-6">

                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Select Account Type</label>
                                                        <div class="input-group">
                                                            <select name="acct_type" class='selectpicker' data-width='100%' required>
                                                                <option value="">Select Account Type</option>
                                                                <option value="Savings">Savings Account</option>
                                                                <option value="Current">Current Account</option>
                                                                <option value="Checking">Checking Account</option>
                                                                <option value="Fixed Deposit">Fixed Deposit</option>
                                                                <option value="Non Resident">Non Resident Account</option>
                                                                <option value="Online Banking">Online Banking</option>
                                                                <option value="Domicilary Account">Domicilary Account</option>
                                                                <option value="Joint Account">Joint Account</option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="">Naration/Purpose</label>
                                                        <div class="input-group ">
                                                            <textarea class="form-control mb-4" rows="3" id="textarea-copy" placeholder="Fund Description" name="acct_remarks"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <button class="btn btn-primary mb-2 mr-2" name="domestic-transfer-start"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                            <polyline points="16 17 21 12 16 7"></polyline>
                                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                                        </svg> Transfer</button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert custom-alert-1 mb-4 bg-danger border-danger" role="alert">

                                                    <div class="media">
                                                        <div class="alert-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                                <line x1="12" y1="16" x2="12" y2="16"></line>
                                                            </svg>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="alert-text">
                                                                <strong>Warning! </strong><span> Account on <span class="text-uppercase "><b>hold</b></span> contact support.</span>
                                                            </div>
                                                            <div class="alert-btn">
                                                                <a class="btn btn-default btn-dismiss" href="mailto:<?= $url_email ?>">Contact Us</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php
            include_once("layouts/footer.php");
            ?>
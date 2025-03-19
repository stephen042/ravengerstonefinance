<?php
ob_start();
session_start();
require_once('../session.php');
require_once("../include/loginFunction.php");
//require_once("../include/userFunction.php");
require_once("../include/userClass.php");
require_once("../include/twilioController.php");



session_start();
if (isset($_SESSION["name"])) {
    if ((time() - $_SESSION['last_login_timestamp']) > 60) // 900 = 15 * 60  
    {
        header("location:logout.php");
    } else {
        $_SESSION['last_login_timestamp'] = time();
        echo "<h1 align='center'>" . $_SESSION["name"] . "</h1>";
        echo '<h1 align="center">' . $_SESSION['last_login_timestamp'] . '</h1>';
        echo "<p align='center'><a href='logout.php'>Logout</a></p>";
    }
}


if (!$_SESSION['acct_no']) {
    header("location:../login.php");
    exit;
}

$sql = "SELECT * FROM settings WHERE id ='1'";
$stmt = $conn->prepare($sql);
$stmt->execute();

$page = $stmt->fetch(PDO::FETCH_ASSOC);

$title = $page['url_name'];

$pageTitle = $title;
$testApi = NULL;

$url_email = $page['url_email'];
$livechat = $page['livechat'];
$trans_limit_min = $page['trans_limit_min'];
$trans_limit_max = $page['trans_limit_max'];



$viesConn = "SELECT * FROM users WHERE acct_no = :acct_no";
$stmt = $conn->prepare($viesConn);

$stmt->execute([
    ':acct_no' => $_SESSION['acct_no']
]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$user_id = $row['id'];

$acct_stat = $row['acct_status'];

// // audit_logs
$sql = "SELECT * FROM audit_logs";
$stmt = $conn->prepare($sql);
$stmt->execute();

$logs = $stmt->fetch(PDO::FETCH_ASSOC);

$device = $logs['device'];
$ipAddress = $logs['ipAddress'];
$datenow = $logs['datenow'];




// // virtual deposit
// $sql7 = "SELECT * FROM virtual";
// $stmt = $conn->prepare($sql7);
// $stmt->execute();

// $deposit = $stmt->fetch(PDO::FETCH_ASSOC);

// $routine_no = $deposit['routine_no'];
// $bank_name = $deposit['bank_name'];
// $swift_code = $deposit['swift_code'];
// $acct_no = $deposit['acct_no'];




// //TEMP TRANSACTION FETCH
$sql = "SELECT * FROM temp_trans WHERE acct_id =:acct_id ORDER BY wire_id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'acct_id' => $user_id
]);
$temp_trans = $stmt->fetch(PDO::FETCH_ASSOC);


$limitRemain = $row['limit_remain'];
// show balance 6,78.76
$acct_balance = $row['acct_balance'];

$fullName = $row['firstname'] . " " . $row['lastname'];
$email = $row['acct_email'];
$currency = currency($row);

$userStatus = userStatus($row);

$title = new pageTitle();
$email_message = new message();
$sendMail = new emailMessage();
$sendSms = new twilioController();


$sql2 = "SELECT * FROM card WHERE user_id=:user_id";
$cardstmt = $conn->prepare($sql2);
$cardstmt->execute([
    'user_id' => $user_id
]);



$cardCheck = $cardstmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type; encoding" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= $pageName ?> - <?= $pageTitle ?> </title>
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico" />
    <link href="../assets/css/loader.css" rel="stylesheet" type="text/css" />
    <?php
    $url_array =  explode('/', $_SERVER['REQUEST_URI']);
    $url = end($url_array);
    ?>
    <script src="../assets/js/loader.js"></script>
    <!--     BEGIN GLOBAL MANDATORY STYLES-->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway|Rock+Salt|Source+Code+Pro:300,400,600" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/forms/custom-clipboard.css">
    <!--     END GLOBAL MANDATORY STYLES-->

    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="../plugins/font-icons/fontawesome/css/fontawesome.css">

    <!--     BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES-->
    <link href="../plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/cards/card.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap-select/bootstrap-select.min.css">
    <!--    profile css-->
    <link rel="stylesheet" type="text/css" href="../plugins/dropify/dropify.min.css">
    <link href="../assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/css/card/card.css">
    <link rel="stylesheet" href="../assets/css/card/displayCard.css">
    <!--    <link href="../assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />-->

    <!--    end of table css-->


    <!-- toaster -->
    <link rel="stylesheet" type="text/css" href="../assets/css/elements/alert.css">
    <link href="../plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="../plugins/sweetalerts/promise-polyfill.js"></script>
    <script src="../assets/js/libs/jquery-3.1.1.min.js"></script>




    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <style>
        @media screen and (max-width: 600px) {
            .layout-visible {
                visibility: hidden;
                clear: both;
                float: left;
                margin: 10px auto 5px 20px;
                width: 28%;
                display: none;
            }
        }
    </style>





</head>

<body class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-nav theme-brand flex-row  text-center">
                <li class="nav-item toggle-sidebar">
                    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-list">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3" y2="6"></line>
                            <line x1="3" y1="12" x2="3" y2="12"></line>
                            <line x1="3" y1="18" x2="3" y2="18"></line>
                        </svg></a>
                </li>
            </ul>


            <ul class="navbar-item flex-row search-ul">
            </ul>
            <ul class="navbar-item flex-row navbar-dropdown">


                <li class="nav-item dropdown message-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-message-circle">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg><span class="badge badge-primary"></span>
                    </a>
                    <div class="dropdown-menu p-0 position-absolute" aria-labelledby="messageDropdown">
                        <?php
                        $acct_id = userDetails('id');

                        $sql2 = "SELECT * FROM loan WHERE acct_id =:acct_id ORDER BY loan_id  DESC LIMIT 3";
                        $wire = $conn->prepare($sql2);
                        $wire->execute([
                            'acct_id' => $acct_id
                        ]);



                        while ($result = $wire->fetch(PDO::FETCH_ASSOC)) {
                            $transStatus = loanStatus($result);
                            $loan_message = $result['loan_message'];
                        ?>
                            <div class="">
                                <a class="dropdown-item">
                                    <div class="">

                                        <div class="media">
                                            <div class="user-img">
                                                <div class="avatar avatar-xl">
                                                    <img src="../assets/profile/<?= $row['image'] ?>" width="100%" alt=""
                                                        style="border-radius: 50%">
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <div class="">
                                                    <h5 class="usr-name text-info text-uppercase"><?= $transStatus ?></h5>
                                                    <p class="msg-title text-danger"><?= $currency . $result['amount'] ?></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </li>

                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class="badge badge-success"></span>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <?php
                        $acct_id = userDetails('id');

                        $sql = "SELECT * FROM transactions LEFT JOIN users ON transactions.user_id =users.id WHERE transactions.user_id =:acct_id order by transactions.trans_id DESC LIMIT 3";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([
                            'acct_id' => $acct_id
                        ]);
                        $sn = 1;
                        while ($seed = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $transStatus = depositStatus($row);

                            if ($seed['trans_type'] === '1') {
                                $trans_type = "<span class='text-success'>Credit [Alert]</span>";
                                $trans_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                            } else if ($seed['trans_type'] === '2') {
                                $trans_type = "<span class='text-danger'>Debit [Alert]</span>";
                                $trans_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
                            }



                        ?>

                            <div class="notification-scroll">
                                <div class="dropdown-item">
                                    <div class="media ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-activity">
                                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                        </svg>
                                        <div class="media-body">
                                            <div class="data-info">
                                                <h6 class=""><?= $trans_type ?></h6>
                                                <p class=""><?= $currency . $seed['amount'] ?></p>
                                            </div>

                                            <div class="icon-status">
                                                <?= $trans_icon ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </li>

                <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-settings">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                            </path>
                        </svg>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="user-profile-section">
                            <div class="media mx-auto">
                                <img src="../assets/profile/<?= $row['image'] ?>" class="img-fluid mr-2" alt="avatar">
                                <div class="media-body">
                                    <h5><?= $fullName ?></h5>
                                    <p><?= $row['acct_type'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <a href="./profile.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-user">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg> <span>My Profile</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="./loan-transaction.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-inbox">
                                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                    <path
                                        d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z">
                                    </path>
                                </svg> <span>My Inbox</span>
                            </a>
                        </div>
                        <div class="dropdown-item">
                            <a href="./logout.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-log-out">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg> <span>Log Out</span>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">
                <div class="profile-info">
                    <figure class="user-cover-image"></figure>
                    <div class="user-info" aria-expanded="true">
                        <img src="../assets/profile/<?= $row['image'] ?>" alt="avatar">
                        <h5><?= $fullName ?></h5>
                        <p class=""><?= $row['acct_type'] ?></p>
                    </div>
                </div>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu <?php active('dashboard.php'); ?>">
                        <a href="./dashboard.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-home">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Dashboard</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu <?php active('deposit.php'); ?>">
                        <a href="./deposit.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                    <g transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                        <path d="M34.801 78.271c5.587-7.722 6.832-17.481 7.039-27.414h17.315v-11.73H41.498c-0.257-12.869 2.023-21.502 6.713-25.123 4.03-3.113 10.752-3.061 19.976 0.163l3.868-11.072C58.854-1.519 48.419-0.971 41.044 4.719c-7.872 6.077-11.587 17.374-11.284 34.408H17.136v11.73h12.963c-0.342 14.225-2.923 22.356-13.2 27.019V90h56.203V78.271H34.801z" fill="grey" />
                                    </g>
                                </svg>

                                <span>Online Deposit</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu <?php active('domestic-transfer.php'); ?> ">
                        <a href="./domestic-transfer.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-share">
                                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                    <polyline points="16 6 12 2 8 6"></polyline>
                                    <line x1="12" y1="2" x2="12" y2="15"></line>
                                </svg>
                                <span>Domestic Transfer</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu <?php active('wire-transfer.php'); ?>">
                        <a href="./wire-transfer.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-wifi">
                                    <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                                    <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                                    <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                    <line x1="12" y1="20" x2="12.01" y2="20"></line>
                                </svg>
                                <span>Wire Transfer</span>
                            </div>


                        </a>
                    </li>
                    <?php
                    if ($cardstmt->rowCount() === 0) {
                    ?>
                        <li class="menu <?php active('card.php'); ?>">
                            <a href="./card.php" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-credit-card">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    <span>Virtual Card</span>
                                </div>


                            </a>
                        </li>
                    <?php
                    } else {
                    ?>
                        <li class="menu <?php active('card.php'); ?>">
                            <a href="./card.php" aria-expanded="false" class="dropdown-toggle">
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-credit-card">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    <span>Virtual Card</span>
                                </div>


                            </a>
                        </li>
                    <?php
                    }
                    ?>

                    <li class="menu <?php active('loan.php'); ?>">
                        <a href="./loan.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-download">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                <span>Loan & Mortgages</span>
                            </div>


                        </a>
                    </li>



                    <li class="menu">
                        <a href="#starkit" data-toggle="collapse" aria-expanded="" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-credit-card">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>Transaction</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="starkit" data-parent="#accordionExample">
                            <li>
                                <a href="./credit-debit_transaction.php"> Credit / Debit Transaction </a>
                            </li>
                            <li>
                                <a href="./wire-transaction.php"> Wire Transaction </a>
                            </li>
                            <li>
                                <a href="./domestic-transaction.php"> Domestic Transaction </a>
                            </li>
                            <li>
                                <a href="loan-transaction.php"> Loan Transaction </a>
                            </li>
                            <li>
                                <a href="deposit-transaction.php"> Deposit Transaction </a>
                            </li>
                        </ul>
                    </li>



                    <li class="menu <?php active('withdrawal.php'); ?>">
                        <a href="./withdrawal.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                    <g transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                        <path d="M34.801 78.271c5.587-7.722 6.832-17.481 7.039-27.414h17.315v-11.73H41.498c-0.257-12.869 2.023-21.502 6.713-25.123 4.03-3.113 10.752-3.061 19.976 0.163l3.868-11.072C58.854-1.519 48.419-0.971 41.044 4.719c-7.872 6.077-11.587 17.374-11.284 34.408H17.136v11.73h12.963c-0.342 14.225-2.923 22.356-13.2 27.019V90h56.203V78.271H34.801z" fill="grey" />
                                    </g>
                                </svg>
                                <span>Withdrawal</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu <?php active('account-manager.php'); ?>">
                        <a href="./account-manager.php" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                    <g transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                                        <path d="M 61.471 53.003 c -2.232 0 -4.048 -1.816 -4.048 -4.048 c 0 -2.232 1.816 -4.048 4.048 -4.048 s 4.048 1.816 4.048 4.048 C 65.519 51.188 63.703 53.003 61.471 53.003 z M 61.471 47.875 c -0.596 0 -1.081 0.485 -1.081 1.081 c 0 0.596 0.485 1.081 1.081 1.081 c 0.596 0 1.081 -0.485 1.081 -1.081 C 62.552 48.36 62.067 47.875 61.471 47.875 z" fill="grey" />
                                        <path d="M 75.222 39.317 h -1.033 V 13.171 c 0 -2.587 -2.105 -4.692 -4.691 -4.692 H 35.51 V 2.272 c 0 -0.918 -0.547 -1.74 -1.393 -2.094 c -0.818 -0.343 -1.753 -0.179 -2.406 0.414 l -4.473 3.63 l -4.473 -3.63 c -0.652 -0.594 -1.586 -0.756 -2.406 -0.414 c -0.846 0.355 -1.393 1.177 -1.393 2.094 V 8.48 h -2.704 c -2.587 0 -4.692 2.105 -4.692 4.692 v 72.138 c 0 2.587 2.105 4.691 4.692 4.691 h 53.236 c 2.587 0 4.691 -2.105 4.691 -4.691 V 58.594 h 1.033 c 1.769 0 3.208 -1.439 3.208 -3.208 v -12.86 C 78.43 40.756 76.991 39.317 75.222 39.317 z M 71.221 13.171 v 26.145 h -5.138 v -27.87 h 3.414 C 70.448 11.447 71.221 12.22 71.221 13.171 z M 21.932 3.737 L 26 7.038 c 0.717 0.583 1.757 0.585 2.475 0.001 l 4.068 -3.301 V 8.48 H 21.932 V 3.737 z M 14.537 85.309 V 13.171 c 0 -0.951 0.774 -1.724 1.724 -1.724 h 46.855 v 27.87 h -1.809 c -5.315 0 -9.638 4.324 -9.638 9.639 c 0 5.315 4.323 9.638 9.638 9.638 h 1.809 v 28.439 H 16.261 C 15.31 87.033 14.537 86.259 14.537 85.309 z M 71.221 85.309 c 0 0.95 -0.774 1.724 -1.724 1.724 h -3.414 V 58.594 h 5.138 V 85.309 z M 61.307 55.379 c -3.542 0 -6.424 -2.882 -6.424 -6.424 c 0 -3.542 2.882 -6.424 6.424 -6.424 l 13.909 -0.006 l 0.006 12.854 H 61.307 z" fill="grey" />
                                    </g>
                                </svg>

                                <span>Account Manager</span>
                            </div>


                        </a>
                    </li>

                    <li class="menu">
                        <a href="#starter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-settings">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg>
                                <span>Settings</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#accordionExample">
                            <li>
                                <a href="./profile.php"> Profile </a>
                            </li>
                            <li>
                                <a href="./edit-profile.php"> Account </a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->
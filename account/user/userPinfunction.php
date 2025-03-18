<?php
require_once ('../include/userClass.php');
use Twilio\TwiML\Voice\Echo_;

$email = $row['acct_email'];
$account_id = $row['id'];

if (isset($_POST['wire_transfer'])) {
    $amount = inputValidation($_POST['amount']);
    $acct_name = inputValidation($_POST['acct_name']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_country = inputValidation($_POST['acct_country']);
    $acct_swift = inputValidation($_POST['acct_swift']);
    $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    $limit_balance = $row['acct_limit'];
    $account_balance = $row['acct_balance'];

    // Fetch last two transactions for the user
    $sql = "SELECT amount, prev_acct_limit FROM wire_transfer WHERE acct_id = :acct_id ORDER BY created_at DESC LIMIT 2";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['acct_id' => $account_id]);
    $recentTransfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count only the last two transfers that were processed under a stored limit 
    // that is less than or equal to the current limit
    $limitCount = count(array_filter($recentTransfers, function ($transaction) use ($limit_balance) {
        return $transaction['prev_acct_limit'] == $limit_balance;
    }));

    // Block the transfer if both of the two most recent transfers were under the current limit
    if ($limitCount >= 2) {
        toast_alert('error', 'You have exceeded your transfer limit. Contact support for an upgrade.');
    } elseif ($amount > $row['acct_limit']) {
        toast_alert("error", "Your transfer limit is " . $row['acct_limit']);
    }elseif ($amount > $account_balance) {
        toast_alert('error', 'Insufficient Balance');
    } elseif ($amount <= 0) {
        toast_alert('error', 'Invalid Amount');
    } else {
        // Proceed with transfer logic
        $trans_id = uniqid();
        $trans_opt = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $sql = "INSERT INTO temp_trans (amount, trans_id, acct_id, bank_name, acct_name_id, acct_number, acct_type, acct_country, acct_swift, acct_routing, acct_remarks, trans_otp) 
                    VALUES (:amount, :trans_id, :acct_id, :bank_name, :acct_name, :acct_number, :acct_type, :acct_country, :acct_swift, :acct_routing, :acct_remarks, :trans_otp)";
        $tranfered = $conn->prepare($sql);
        $tranfered->execute([
            'amount' => $amount,
            'trans_id' => $trans_id,
            'acct_id' => $account_id,
            'bank_name' => $bank_name,
            'acct_name' => $acct_name,
            'acct_number' => $acct_number,
            'acct_type' => $acct_type,
            'acct_country' => $acct_country,
            'acct_swift' => $acct_swift,
            'acct_routing' => $acct_routing,
            'acct_remarks' => $acct_remarks,
            'trans_otp' => $trans_opt
        ]);

        // Generate OTP and notify user
        $acct_otp = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $sql = "UPDATE users SET acct_otp = :acct_otp WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['acct_otp' => $acct_otp, 'id' => $account_id]);

        // Redirect based on billing code
        session_start();
        $_SESSION['wire-transfer'] = ($row['billing_code'] === '0') ? $acct_otp : $user_id;
        header("Location:" . ($row['billing_code'] === '0' ? "./pin.php" : "./cot.php"));
    }
}


if (isset($_POST['cot_submit'])) {
    $cotCode = $_POST['cot_code'];
    $acct_cot = $row['acct_cot'];

    if ($cotCode === $acct_cot) {
        $_SESSION['wire-transfer'] = $user_id;
        header("Location:./tax.php");
    } else {
        notify_alert('Invalid COT Code', 'danger', '3000', 'Close');
    }
}

if (isset($_POST['tax_submit'])) {
    $taxCode = $_POST['tax_code'];
    $acct_tax = $row['acct_tax'];

    if ($taxCode === $acct_tax) {
        $_SESSION['wire-transfer'] = $user_id;
        header("Location:./imf-code.php");
    } else {
        notify_alert('Invalid TAX Code', 'danger', '3000', 'Close');
    }
}


if (isset($_POST['imf_submit'])) {
    $imf_code = $_POST['imf_code'];
    $imf = $row['acct_imf'];
    $amount = $temp_trans['amount'];

    if ($imf_code === $imf) {
        $sql3 = "SELECT * FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql3);
        $stmt->execute([
            'id' => $account_id
        ]);
        $resultCode = $stmt->fetch(PDO::FETCH_ASSOC);
        $code = $resultCode['acct_otp'];

        $number = $resultCode['acct_phone'];
        $message = "Dear " . $resultCode['firstname'] . "Your verify code is " . $code;

        // $data = twilioController::sendSmsCode($number,$message);

        $APP_NAME = $pageTitle;
        $currency = "$";
        $fullName = $resultCode['firstname'] ." ". $resultCode['lastname'];
        $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
        $subject = "[OTP CODE] - $APP_NAME";
        $email_message->send_mail($email, $message, $subject);

        if (true) {
            $_SESSION['wire-transfer'] = $user_id;
            header("Location:./pin.php");
        } else {
            notify_alert('Invalid IMF Code', 'danger', '3000', 'Close');
        }
    }
}

if (isset($_POST['submit-pin'])) {
    $pin = inputValidation($_POST['pin']);
    $oldPin = inputValidation($row['acct_otp']);
    $acct_amount = inputValidation($row['acct_balance']);
    $account_id = inputValidation($_POST['account_id']);
    $amount = inputValidation($_POST['amount']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_name = inputValidation($_POST['acct_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_type = inputValidation($_POST['acct_type']);
    $acct_country = inputValidation($_POST['acct_country']);
    $acct_swift = inputValidation($_POST['acct_swift']);
    $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    $limit_balance = $row['acct_limit'];
    $transferLimit = $row['limit_remain'];

    if ($pin !== $oldPin) {
        toast_alert('error', 'Incorrect OTP CODE');
    } else if ($acct_amount < 0) {
        toast_alert('error', 'Insufficient Balance');
    } else {

        // $tBalance = ($transferLimit - $amount);
        $aBalance = ($acct_amount - $amount);


        $sql = "UPDATE users SET acct_balance=:acct_balance WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'acct_balance' => $aBalance,
            'id' => $account_id
        ]);

        if (true) {
            $refrence_id = uniqid();
            $sql = "INSERT INTO wire_transfer (amount,acct_id,refrence_id,bank_name,acct_name,acct_number,acct_type,acct_country,acct_swift,acct_routing,acct_remarks,prev_acct_limit) VALUES(:amount,:acct_id,:refrence_id,:bank_name,:acct_name,:acct_number,:acct_type,:acct_country,:acct_swift,:acct_routing,:acct_remarks,:prev_acct_limit)";
            $tranfered = $conn->prepare($sql);
            $tranfered->execute([
                'amount' => $amount,
                'acct_id' => $account_id,
                'refrence_id' => $refrence_id,
                'bank_name' => $bank_name,
                'acct_name' => $acct_name,
                'acct_number' => $acct_number,
                'acct_type' => $acct_type,
                'acct_country' => $acct_country,
                'acct_swift' => $acct_swift,
                'acct_routing' => $acct_routing,
                'acct_remarks' => $acct_remarks,
                'prev_acct_limit' => $limit_balance
            ]);

            if (true) {
                session_start();
                $_SESSION['wire_transfer'] = $refrence_id;
                header("Location:./success.php");
            } else {
                toast_alert("error", "Sorry Error Occurred Contact Support");
            }
        }
    }
}


if (isset($_POST['domestic-transfer-start'])) {

    $amount = $_POST['amount'];
    $acct_name = $_POST['acct_name'];
    $bank_name = $_POST['bank_name'];
    $acct_number = $_POST['acct_number'];
    $acct_type = $_POST['acct_type'];
    $acct_remarks = $_POST['acct_remarks'];

    $acct_amount = $row['acct_balance'];
    $account_id = $row['id'];


    if ($acct_stat === 'hold') {
        toast_alert("error", "Account on Hold Contact Support");
    } elseif ($row['acct_limit'] === 0) {
        toast_alert("error", "You have Exceed Your Transfer Limit");
    } elseif ($amount > $row['acct_limit']) {
        toast_alert("error", "Your transfer limit is " . $row['acct_limit']);
    } elseif ($amount > $acct_amount) {
        toast_alert("error", "Insufficient Balance!");
    } else {
        $trans_id = uniqid();
        $trans_opt = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $trans_type = "domestic transfer";
        $sql = "INSERT INTO temp_trans (amount,trans_id,acct_id,bank_name,acct_name_id,acct_number,acct_type,acct_remarks,trans_otp,trans_type) VALUES(:amount,:trans_id,:acct_id,:bank_name,:acct_name,:acct_number,:acct_type,:acct_remarks,:trans_otp,:trans_type )";
        $tranfered = $conn->prepare($sql);
        $tranfered->execute([
            'amount' => $amount,
            'trans_id' => $trans_id,
            'acct_id' => $account_id,
            'bank_name' => $bank_name,
            'acct_name' => $acct_name,
            'acct_number' => $acct_number,
            'acct_type' => $acct_type,
            'acct_remarks' => $acct_remarks,
            'trans_otp' => $trans_opt,
            'trans_type' => $trans_type
        ]);

        if (true) {
            //            $TRANS = uniqid('w', true);
            $trans_id = mt_rand(100000, 999999);
            $trans_opt = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            $sql = "UPDATE users SET acct_otp=:acct_otp WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'acct_otp' => $trans_opt,
                'id' => $account_id
            ]);

            if (true) {
                $sql = "SELECT * FROM users WHERE id=:id";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    'id' => $account_id
                ]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $code = $result['acct_otp'];

                $number = $result['acct_phone'];
                $message = "Dear " . $result['firstname'] . "Your verify code is " . $code;

                //  $data = twilioController::sendSmsCode($number,$message);

                $APP_NAME = $pageTitle;
                $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
                $subject = "[OTP CODE] - $APP_NAME";
                $email_message->send_mail($email, $message, $subject);
            }

            if (true) {
                session_start();
                $_SESSION['dom-transfer'] = $code;
                header("Location:./pin.php");
            }


            //  if (true) {
            //         if($row['billing_code']==='0') {

            //             $sql = "SELECT * FROM users WHERE id=:id";
            //             $stmt = $conn->prepare($sql);
            //             $stmt->execute([
            //                 'id' => $account_id
            //             ]);
            //             $resultCode = $stmt->fetch(PDO::FETCH_ASSOC);
            //             $code = $resultCode['acct_otp'];

            //             $APP_NAME = $pageTitle;
            //             $message = $sendMail->pinRequest($currency, $amount, $fullName, $code, $APP_NAME);
            //             $subject = "[OTP CODE] - $APP_NAME";
            //             $email_message->send_mail($email, $message, $subject);

            //             if(true){
            //                 session_start();
            //                 $_SESSION['dom-transfer'] = $code;
            //                     header("Location:./pin.php");
            //             }
            //         }else{
            //             session_start();
            //             $_SESSION['dom-transfer']=$user_id;
            //             header("Location:./cot.php");
            //         }
            //     }



        }
    }
}

if (isset($_POST['domestic-transfer-end'])) {
    $pin = inputValidation($_POST['pin']);
    $oldPin = inputValidation($row['acct_otp']);
    $acct_amount = inputValidation($row['acct_balance']);
    $account_id = inputValidation($_POST['account_id']);
    $amount = inputValidation($_POST['amount']);
    $bank_name = inputValidation($_POST['bank_name']);
    $acct_name = inputValidation($_POST['acct_name']);
    $acct_number = inputValidation($_POST['acct_number']);
    $acct_type = inputValidation($_POST['acct_type']);
    // $acct_country = inputValidation($_POST['acct_country']);
    // $acct_swift = inputValidation($_POST['acct_swift']);
    // $acct_routing = inputValidation($_POST['acct_routing']);
    $acct_remarks = inputValidation($_POST['acct_remarks']);

    $limit_balance = $row['acct_limit'];
    $transferLimit = $row['limit_remain'];

    if ($pin !== $oldPin) {
        toast_alert('error', 'Incorrect OTP CODE');
    } else if ($acct_amount < 0) {
        toast_alert('error', 'Insufficient Balance');
    } else {

        // $tBalance = ($transferLimit - $amount);
        $aBalance = ($acct_amount - $amount);


        $sql = "UPDATE users SET acct_balance=:acct_balance WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'acct_balance' => $aBalance,
            'id' => $account_id
        ]);

        if (true) {
            $refrence_id = uniqid();
            $trans_type = "Domestic Transfer";
            $dom_status = 0;

            $sql = "INSERT INTO domestic_transfer (amount,acct_id,refrence_id,bank_name,acct_name,acct_number,acct_type,acct_remarks,trans_type,dom_status) VALUES(:amount,:acct_id,:refrence_id,:bank_name,:acct_name,:acct_number,:acct_type,:acct_remarks,:trans_type,:dom_status)";
            $tranfered = $conn->prepare($sql);
            $tranfered->execute([
                'amount' => $amount,
                'acct_id' => $account_id,
                'refrence_id' => $refrence_id,
                'bank_name' => $bank_name,
                'acct_name' => $acct_name,
                'acct_number' => $acct_number,
                'acct_type' => $acct_type,
                'acct_remarks' => $acct_remarks,
                'trans_type' => $trans_type,
                'dom_status' => $dom_status
            ]);

            if (true) {
                session_start();
                $_SESSION['dom_transfer'] = $refrence_id;
                header("Location:./success.php");
            } else {
                toast_alert("error", "Sorry Error Occurred Contact Support");
            }
        }
    }
}

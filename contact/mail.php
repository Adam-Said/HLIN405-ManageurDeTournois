<?php $name = $_POST['name'];

$email = $_POST['email'];

$message = $_POST['message'];

$dropdown = $_POST['dropdown'];

$formcontent="From: $name \n Subject: $dropdown \n Message: $message";

utf8_encode($formcontent);

$recipient = "contact@tournoi.adam-net.fr";

$subject = $name . " pour " . $dropdown;

utf8_encode($subject);

$mailheader = "From: $email \r\n";

utf8_encode($mailheader);

mail($recipient, $subject, $formcontent, $mailheader) or ("Error!");

echo '<html>
                    <head>
                        <meta http-equiv="refresh" content="3;url=../registration" />
                        <link rel="stylesheet" type="text/css" href="../teamsignup/index.css">
                        <link rel="preconnect" href="https://fonts.gstatic.com">
                    </head>
                    <body>
                    <header>
                        <h1 id="title">Manageur de tournois</h1>
                    </header>
                    <main>
                    <h4 id="serverh4">Message envoyé ! Nous vous répondrons dès que possible.</h4>
                        <img id="serverload" src="../media/loading.gif">
                    </main>
                    </body>
                </html>';

?>
<table class="main">
    <tr>
        <td class="wrapper">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p>Hi <?= $dpoName ?>,</p>
                        <p>You have received a new contact form message from a data subject.</p>

                        <p>
                            <strong>Name: </strong>
                            <?= $first_name ?> <?= $last_name ?>
                        </p>
                        <p>
                            <strong>Email: </strong>
                            <?= $email ?>
                        </p>
                        <p>
                            <strong>Message: </strong><br>
                            <?= $message ?>
                        </p>   


                         <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                            <tbody>
                                <tr>
                                    <td align="left">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="mailto:<?= $email ?>" target="_blank">Reply</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>      
                                       
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
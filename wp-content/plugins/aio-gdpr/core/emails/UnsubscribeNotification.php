<table class="main">
    <tr>
        <td class="wrapper">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p>Hi <?= $dpoName ?>,</p>
                        <p>You have received a new unsubscribe request from a data subject.</p>
                        <p>This individual would like to be removed from all of your email marketing lists.</p>

                        <p>
                            <strong>Name: </strong>
                            <?= $first_name ?> <?= $last_name ?>
                        </p>
                        <p>
                            <strong>Email: </strong>
                            <?= $email ?>
                        </p>             
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
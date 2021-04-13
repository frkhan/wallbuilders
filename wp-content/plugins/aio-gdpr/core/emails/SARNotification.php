<table class="main">
    <tr>
        <td class="wrapper">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p>Hi <?= $dpoName ?>,</p>
                        <p>You have received a new subject access request from a data subject.</p>
                        <p>This individual would like to know what personal data you possess on them.</p>

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
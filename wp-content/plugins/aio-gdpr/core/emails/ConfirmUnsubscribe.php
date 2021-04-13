<table class="main">
    <tr>
        <td class="wrapper">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p>Hi <?= $name ?>,</p>
                        <p>We have just received your unsubscribe request.</p>
						<p>Please click on the button below to confirm you would like us to remove you from all marketing emails.</p>

						<table border="0" cellpadding="0" cellspacing="0" class="btn btn-danger">
                            <tbody>
                                <tr>
                                    <td align="left">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <a href="<?= $confirmLink ?>" target="_blank">Confirm</a>
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
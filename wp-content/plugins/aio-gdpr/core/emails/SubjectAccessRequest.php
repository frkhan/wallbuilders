<table class="main">
    <tr>
        <td class="wrapper">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p>Hi <?= $name ?>,</p>
                        <p>Your subject access request has been processed. Below is a table explaining what data we currently possess on you.</p>                    
                        <p>We have found a total of <?= $count ?> pieces of your personal data.</p>

                        <table>
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data as $type => $rows): ?>
                                    <tr>
                                        <td><?= AIOGDPR_DataCollecter::formatDataType($type) ?></td>
                                        <td>
                                            <?php foreach($rows as $row): ?>
                                                <?= $row->data ?><br/>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>                  
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
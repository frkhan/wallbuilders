<?php if($request->sar_has_collected == '1'): ?>
    <table style="width:100%">
        <tr>
            <td><strong>Type</strong></td>
            <td><strong>Data</strong></td>
        </tr>
        
        <?php if(is_array($request->sar_user_data)): ?>
            <?php foreach($request->sar_user_data as $type => $rows): ?>
                <tr>
                    <td><?= AIOGDPR_DataCollecter::formatDataType($type) ?></td> 
                    <td>
                        <?php foreach($rows as $row): ?>
                            <?= $row->data ?><br/>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
<?php endif; ?>

<br>
<a href="<?= AIOGDPR_FindDataAction::url(array('request_id' => $request->ID)) ?>" class="button button-primary">Auto-find user data</a>


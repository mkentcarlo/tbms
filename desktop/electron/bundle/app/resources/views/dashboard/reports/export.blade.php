<table>
    <tbody>
        <?php 
        foreach($exportdata as $k => $data): 
        ?>
            <tr>
                <?php foreach($data as $d): 
                    $d = str_replace('&', 'and', $d); ?>
                    <td {{$k == 0 ? 'colspan=5' : ''}}><?php echo stripslashes($d); ?></td> 
                    <?php 
            endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<table>
    <tbody>
        <?php 
        foreach($exportdata as $k => $data): 
        if($k > 30){
            continue;
        }
        ?>
            <tr>
                <?php foreach($data as $d): ?>
                    <td><?php echo stripslashes($id); ?></td> 
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
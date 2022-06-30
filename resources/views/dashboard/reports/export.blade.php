<table>
    <tbody>
        <?php 
        foreach($exportdata as $data): ?>
            <tr>
                <?php foreach($data as $d): ?>
                    <td><?php echo str_replace('/', '', $d); ?></td> 
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<table class="table table-bordered">
    <tbody>
        <?php 
        foreach($exportdata as $data): ?>
            <tr>
                <?php foreach($data as $d): ?>
                    <td><?php echo $d; ?></td> 
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
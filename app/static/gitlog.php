<div class="container">
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">Git log</h3>
          </div>
          <div class="panel-body">
                <table class="table table-hover gitlog">
                    <thead>
                    </thead>
                    <tbody>
                    <?php foreach ($gitlogs as $gitlog): ?>

                        <tr class="<?php echo $gitlog['hash']; ?>">
                            <td><?php echo $gitlog['author']; ?></td>
                            <td><?php echo $gitlog['date']; ?></td>
                            <td><?php echo $gitlog['dater']; ?></td>
                            <td><?php echo $gitlog['message']; ?></td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
          </div>
    </div>
</div>

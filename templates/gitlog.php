<?php $gitlogs = $this->controller->gitLogs(); ?>
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

                        <?php if (isset($gitlog['deployed']) && $gitlog['deployed'] == true): ?>

                            <tr style="background-color: #DFF0D8">
                                <td colspan="4"><span class="glyphicon glyphicon-cloud-upload"></span> Deployed <?php echo $gitlog['deploydate']; ?></td>
                            </tr>

                        <?php endif; ?>

                        <tr class="">
                            <td><?php echo $gitlog['author']; ?></td>
                            <td><?php echo $gitlog['date']; ?></td>
                            <td><?php echo $gitlog['dater']; ?></td>
                            <td><a href="http://gitlab.thebeautyst.org/thebeautyst/thebeautyst/commit/<?php echo $gitlog['hash']; ?>" target="_blank"><?php echo truncateHash((string) $gitlog['hash']); ?></a> <?php echo preg_replace('/#([0-9]*)/', "<a href='http://redmine.thebeautyst.org/issues/$1' target='_blank'>#$1</a>", $gitlog['message']); ?></td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
          </div>
    </div>
</div>

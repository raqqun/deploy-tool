<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>TheBeautyst Deploy Tool</title>

<!-- Bootstrap -->
<link href="static/css/bootstrap.min.css" rel="stylesheet">
<!-- Deploy App css -->
<link rel="stylesheet" type="text/css" href="static/css/deploy.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
    <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><span class="glyphicon glyphicon-cloud"></span>TheBeautyst Deploy</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li id="preprod"><a href="http://preprod.thebeautyst.org" target="_blank"><img src="static/img/logo_thebeautyst.png">Preproduction Server</a></li>
                <li id="redmine"><a href="http://redmine.thebeautyst.org" target="_blank"><img src="static/img/redmine.png">Redmine</a></li>
                <li id="gitlab"><a href="http://gitlab.thebeautyst.org" target="_blank"><img src="static/img/gitlab.png">Gitlab</a>
                <li id="server-perf"><a href="#"><span class="glyphicon glyphicon-dashboard"></span>Server Monitoring</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">fun stuff <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Settings</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
    <!-- Deploy-charging Modal -->
    <div style="top:200px;" class="modal fade" id="deploy-charging-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h1 style="text-align: center;" class="modal-title" id="myModalLabel"></h1>
                </div>
            </div>
        </div>
    </div>
    <!-- Deploy Modal -->
    <div class="modal fade" id="deploy-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="myModalLabel">Rsync Dry-run Log</h1>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

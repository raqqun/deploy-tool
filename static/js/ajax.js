$('.action').on('click', function(e) {
    var self = $(this);

    var action = self.data('action');

    jQuery.ajax({
        type: 'POST',
        url: 'http://deploy.thebeautyst.org/app/ajax.php?action=' + action,
        beforeSend: function () {
            if(action == 'deploy-dryrun') {
                $('.close-modal').hide();
                $('.deploy').show();
                $('#deploy-modal .modal-body').html('');
                $('#deploy-charging-modal h1').html("Deploying Dry-run <span class=\"glyphicon glyphicon-cloud-upload\"></span>");
                $('#deploy-charging-modal').modal({
                    backdrop: 'static'
                });
            }
        },
        success: function (response) {
            if(action == 'pull') {
                var pull = JSON.parse(response);
                $('.reports').html('');
                $('.reports').append("<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>"+pull.gitpull.join('<br>')+"</div>");
                $('.gitlog tbody').html('');

                for(log in pull.gitlog) {
                    if (pull.gitlog[log].deployed == true) {
                        $('.gitlog tbody').append("<tr style=\"background-color: #DFF0D8\"><td colspan=\"4\"><span class=\"glyphicon glyphicon-cloud-upload\"></span> Deployed "+pull.gitlog[log].deploydate+"</td></tr>");
                    }
                    $('.gitlog tbody').append("<tr class='"+pull.gitlog[log].hash+"'><td>"+pull.gitlog[log].author+"</td><td>"+pull.gitlog[log].date+"</td><td>"+pull.gitlog[log].dater+"</td><td><a href='http://gitlab.thebeautyst.org/thebeautyst/thebeautyst/commit/"+pull.gitlog[log].hash+"'>"+pull.gitlog[log].hash.substring(0, 9)+"</a> "+pull.gitlog[log].message+"</td></tr>");
                }
            }
            else if(action == 'deploy-dryrun') {
                var deploy = JSON.parse(response);

                $('#deploy-charging-modal').modal('hide');
                $('#deploy-modal').modal({
                    backdrop: 'static'
                });

                for(key in deploy) {
                    for(file in deploy[key].createdfiles) {
                        $('#deploy-modal .modal-body').append("<p><b>Created Files:</b><br>"+deploy[key].createdfiles[file].join('<br>')+"</p>");
                    }
                    for(file in deploy[key].createdirectories) {
                        $('#deploy-modal .modal-body').append("<p><b>Created Directorys:</b><br>"+deploy[key].createdirectories[file].join('<br>')+"</p>");
                    }
                    for(file in deploy[key].modifiedfiles) {
                        $('#deploy-modal .modal-body').append("<p><b>Modified Files:</b><br>"+deploy[key].modifiedfiles[file].join('<br>')+"</p>");
                    }
                }
            }
            else if (action == 'import') {
                $('#deploy-charging-modal h1').html(response+" <span class=\"glyphicon glyphicon-transfer\"></span>");
                $('#deploy-charging-modal').modal();
            }
        }
    });
});


$('#deploy-modal .deploy').on('click', function(e) {
    var self = $(this);

    var action = self.data('action');
    console.log(action);
    jQuery.ajax({
        type: 'POST',
        url: 'http://deploy.thebeautyst.org/app/ajax.php?action=' + action,
        beforeSend: function() {
            $('#deploy-modal .modal-body').html('');
            $('#deploy-charging-modal h1').html("Deploying <span class='glyphicon glyphicon-cloud-upload'>");
            $('#deploy-modal h1').html("Rsync Deploy Log");

            $('#deploy-modal').modal('hide');
            $('#deploy-charging-modal').modal({
                backdrop: 'static'
            });
        },
        success: function(response) {
            var deploy = JSON.parse(response);
            $('.close-modal').show();
            self.hide();
            $('#deploy-charging-modal').modal('hide');
            $('#deploy-modal').modal({
                backdrop: 'static'
            });

            for(key in deploy) {
                for(file in deploy[key].createdfiles) {
                    $('#deploy-modal .modal-body').append("<p><b>Created Files:</b><br>"+deploy[key].createdfiles[file].join('<br>')+"</p>");
                }
                for(file in deploy[key].createdirectories) {
                    $('#deploy-modal .modal-body').append("<p><b>Created Directorys:</b><br>"+deploy[key].createdirectories[file].join('<br>')+"</p>");
                }
                for(file in deploy[key].modifiedfiles) {
                    $('#deploy-modal .modal-body').append("<p><b>Modified Files:</b><br>"+deploy[key].modifiedfiles[file].join('<br>')+"</p>");
                }
            }
        }
    });
});

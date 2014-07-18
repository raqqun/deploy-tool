$('.action').on('click', function(e) {
    var self = $(this);

    var action = self.data('action');

    jQuery.ajax({
        type: 'POST',
        url: 'http://deploy.thebeautyst.org/config/ajax.php?action=' + action,
        beforeSend: function () {
            $('#deploy-modal .modal-body').html('');
            $('#deploy-charging-modal h1').html('');
            if(action == 'deploy-dryrun') {
                $('#deploy-charging-modal').modal({
                    backdrop: 'static'
                });
            }
        },
        success: function (response) {
            if(action == 'pull') {
                var pull = JSON.parse(response);
                $('.reports').html('');
                $('.reports').append("<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>"+pull.gitpull+"</div>");
                $('.gitlog tbody').html('');

                for(log in pull.gitlog) {
                    $('.gitlog tbody').append("<tr class='"+pull.gitlog[log].hash+"'><td>"+pull.gitlog[log].author+"</td><td>"+pull.gitlog[log].date+"</td><td>"+pull.gitlog[log].dater+"</td><td>"+pull.gitlog[log].message+"</td></tr>");
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
        }
    });
});


$('#deploy-modal .deploy').on('click', function(e) {
    var self = $(this);

    var action = self.data('action');
    console.log(action);
    jQuery.ajax({
        type: 'POST',
        url: 'http://deploy.thebeautyst.org/config/ajax.php?action=' + action,
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

            $('#deploy-charging-modal').modal('hide');
            $('#deploy-modal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>')
            $('#deploy-modal').modal({
                    backdrop: 'static'
            });

            for(key in deploy) {
                for(file in deploy[key].createdfiles) {
                    $('#deploy-modal .modal-body').append("<p><b>Created Files:</b> "+deploy[key].createdfiles[file].join('<br>')+"</p>");
                }
                for(file in deploy[key].createdirectories) {
                    $('#deploy-modal .modal-body').append("<p><b>Created Directorys:</b> "+deploy[key].createdirectories[file].join('<br>')+"</p>");
                }
                for(file in deploy[key].modifiedfiles) {
                    $('#deploy-modal .modal-body').append("<p><b>Modified Files:</b> "+deploy[key].modifiedfiles[file].join('<br>')+"</p>");
                }
            }
        }
    });
});

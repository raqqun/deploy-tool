<?php


class Controller {

    private $gitPaths;

    public function __construct() {
        $this->gitPaths = json_decode(file_get_contents(APP_DIR.'/paths.json'), $assoc = true);
    }

    public function gitLogs() {
        $response = array();
        $i=0;

        $gitPaths = $this->gitPaths;

        exec("cd {$gitPaths['gitLocalRepository']}; git log --date=short --pretty=format:\"<hash>%H<hash> <author>%an<author> <date>%ad<date> <dater>%ar<dater> <message>%s<message>\" -36;", $gitlogs);

        foreach ($gitlogs as $gitlog) {

            preg_match_all("/<hash>([a-z0-9\W]+)<hash> <author>([a-zA-Z0-9\W]+)<author> <date>([a-z0-9\W]+)<date> <dater>([a-z0-9\W]+)<dater> <message>([a-zA-Z0-9_\W]+)<message>/", $gitlog, $matches);

            $response['commit'.$i]['hash'] = $matches[1][0];
            $response['commit'.$i]['author'] = $matches[2][0];
            $response['commit'.$i]['date'] = $matches[3][0];
            $response['commit'.$i]['dater'] = $matches[4][0];
            $response['commit'.$i]['message'] = $matches[5][0];

            if (file_exists(ABSPATH.'/since_last_log.json')) {
                $lastCommits = json_decode(file_get_contents(ABSPATH.'/since_last_log.json'), true);

                foreach ($lastCommits['lastcommit'] as $commit) {
                    if ($commit[0] == $response['commit'.$i]['hash']) {
                        $response['commit'.$i]['deployed'] = true;
                        $response['commit'.$i]['deploydate'] = $commit[1];
                    }
                }
            }

            $i++;
        }

        return $response;
    }


    public function gitPull() {
        $response = array();

        $gitPaths = $this->gitPaths;

        exec("cd {$gitPaths['gitLocalRepository']}; git pull --rebase 2>&1;", $gitpull);
        $gitlogs = $this->gitLogs();

        $response['gitpull'] = $gitpull;
        $response['gitlog'] = $gitlogs;

        return json_encode($response);
    }


    public function deployToProduction($dryRun = false) {

        $gitPaths = $this->gitPaths;

        $dryRunOption = ($dryRun) ? 'n' : '';

        exec("rsync -auvz".$dryRunOption." --itemize-changes --exclude-from '".APP_DIR."/rsync_prod_srv' -e 'ssh -i {$gitPaths['rsyncPrivatekey']}' {$gitPaths['gitLocalRepository']} {$gitPaths['rsyncRemoteUser']}:{$gitPaths['rsyncRemotePath']};", $rsynclog);

        $createdFiles = array();
        $currentDate = date('d/m/Y G:s');

        foreach ($rsynclog as $logline) {
            if(preg_match('/(^<f\+\+\+\+\+\+\+\+\+) ([a-z0-9_\W]+)/i', $logline, $matches)) {
                $createdFiles[$currentDate]['createdfiles']['filename'][] = $matches[2];
            }
            elseif(preg_match('/(^cd\+\+\+\+\+\+\+\+\+) ([a-z0-9_\W]+)/i', $logline, $matches)) {
                $createdFiles[$currentDate]['createdirectories']['filename'][] = $matches[2];
            }
            elseif(preg_match('/(^<f[a-z\W]{9}) ([a-z0-9_\W]+)/i', $logline, $matches)) {
                $createdFiles[$currentDate]['modifiedfiles']['filename'][] = $matches[2];
            }
        }

        $jsonify_log = json_encode($createdFiles);

        file_put_contents(ABSPATH.'/rsync_log.json', $jsonify_log);

        $lastLog = exec("cd {$gitPaths['gitLocalRepository']}; git log --date=short --pretty=format:\"%H\" -1");

        if (file_exists(ABSPATH.'/since_last_log.json') && !$dryRun) {
            $lastCommits = json_decode(file_get_contents(ABSPATH.'/since_last_log.json'), true);

            $lastCommitHash = end($lastCommits['lastcommit']);
            if ($lastCommitHash[0] != $lastLog) {
                $lastCommits['lastcommit'][] = array($lastLog, date('d/m/Y h:i'));
            }

            file_put_contents(ABSPATH.'/since_last_log.json', json_encode($lastCommits));

        }
        elseif (!file_exists(ABSPATH.'/since_last_log.json') && !$dryRun) {
            $lastcommit = array("lastcommit"=>array(array($lastLog, date('d/m/Y h:i'))));
            file_put_contents(ABSPATH.'/since_last_log.json', json_encode($lastcommit));
        }

        return $jsonify_log;
    }


    function importLatestDatabase() {
        $date = date('d_m_y');
        $log = array();
        exec("/home/server/prod_preprod_sync/importprod.sh http://preprod.thebeautyst.org/ http://preprod.thebeautyst.co.uk/ /media/samba/dumps/thebeautyst_latest_dump.sql.gz > ".ABSPATH."/import.log 2>&1", $log);
    }


    function printImportLatestDatabaseLog() {
        $log = array();

        if(file_exists(ABSPATH.'/import.log')) {
            $log = file(ABSPATH.'/import.log');
        }

        return $log;
    }
}

$controller = new Controller();

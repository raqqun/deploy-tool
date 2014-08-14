<?php


class Controller {

    private $paths;
    private $absPathToConfig;

    public function __construct() {
        $this->absPathToConfig = dirname(__FILE__);
        $this->paths = json_decode(file_get_contents($this->absPathToConfig.'/paths.json'), $assoc = true);
    }

    public function gitLogs() {
        $response = array();
        $i=0;

        $paths = $this->paths;

        exec("cd {$paths['gitLocalRepository']}; git log --date=short --pretty=format:\"<hash>%H<hash> <author>%an<author> <date>%ad<date> <dater>%ar<dater> <message>%s<message>\" -36;", $gitlogs);

        foreach ($gitlogs as $gitlog) {

            preg_match_all("/<hash>([a-z0-9\W]+)<hash> <author>([a-zA-Z0-9\W]+)<author> <date>([a-z0-9\W]+)<date> <dater>([a-z0-9\W]+)<dater> <message>([a-zA-Z0-9_\W]+)<message>/", $gitlog, $matches);

            $response['commit'.$i]['hash'] = $matches[1][0];
            $response['commit'.$i]['author'] = $matches[2][0];
            $response['commit'.$i]['date'] = $matches[3][0];
            $response['commit'.$i]['dater'] = $matches[4][0];
            $response['commit'.$i]['message'] = $matches[5][0];

            if (file_exists('/var/www/deploy-tool/app/since_last_log.json')) {
                $last_commits = json_decode(file_get_contents('/var/www/deploy-tool/app/since_last_log.json'), true);

                foreach ($last_commits['lastcommit'] as $commit) {
                    if ($commit == $response['commit'.$i]['hash']) {
                        $response['commit'.$i]['deployed'] = true;
                    }
                }
            }

            $i++;
        }

        return $response;
    }


    public function gitPull() {
        $response = array();

        $paths = $this->paths;

        exec("cd {$paths['gitLocalRepository']}; git pull --rebase 2>&1;", $gitpull);
        $gitlogs = $this->gitLogs();

        $response['gitpull'] = $gitpull;
        $response['gitlog'] = $gitlogs;

        return json_encode($response);
    }


    public function deployToProduction($dryRun = false) {

        $paths = $this->paths;

        $dryRunOption = ($dryRun) ? 'n' : '';

        exec("rsync -auvz".$dryRunOption." --itemize-changes --exclude-from '".$this->absPathToConfig."/rsync_prod_srv' -e 'ssh -i {$paths['rsyncPrivatekey']}' {$paths['gitLocalRepository']} {$paths['rsyncRemoteUser']}:{$paths['rsyncRemotePath']};", $rsynclog);

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

        file_put_contents($this->absPathToConfig.'/rsync_log.json', $jsonify_log);

        $last_log = exec("cd {$paths['gitLocalRepository']}; git log --date=short --pretty=format:\"%H\" -1");

        if (file_exists('/var/www/deploy-tool/app/since_last_log.json') && $dryRun == false) {
            $last_commits = json_decode(file_get_contents('/var/www/deploy-tool/app/since_last_log.json'), true);

            if (end($last_commits['lastcommit']) != $last_log) {
                $last_commits['lastcommit'][] = $last_log;
            }
            error_log(print_r($last_commit, true));

            file_put_contents('/var/www/deploy-tool/app/since_last_log.json', json_encode($last_commits));

        } else {
            $lastcommit = array("lastcommit"=>array($last_log));
            file_put_contents('/var/www/deploy-tool/app/since_last_log.json', json_encode($lastcommit));
        }

        return $jsonify_log;
    }
}

$controller = new Controller();

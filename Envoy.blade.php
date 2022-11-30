@setup
    require __DIR__.'/vendor/autoload.php';

    //User for prod
    $user      = 'tirat.egodev1.info';
    $userGroup = 'tirat.egodev1.info';
    $server    = '157.90.1.200';

    $releaseRotate = 5;
    $timezone = 'Asia/Jerusalem';
    $date     = new DateTime('now', new DateTimeZone($timezone));

    $gitRepository = "git@github.com:ego-digital-dev/tirat-carmel-city-hall.git";
    $gitBranch = 'master';

    $chmods = [
        'storage/logs'
    ];

    $basePath          = '/var/www/tirat.egodev1.info/data/www/tirat.egodev1.info';
    $dirCurrent        = $basePath . '/current';
    $dirShared         = $basePath . '/shared';
    $dirReleases       = $basePath . '/releases';
    $dirCurrentRelease = $dirReleases . '/' . $date->format('YmdHis');

    function logMessage($message) {
        return "echo '\033[32m" .$message. "\033[0m';\n";
    }
@endsetup

@servers(['production' => $user . '@' . $server, 'localhost' => '127.0.0.1'])

@task('git', ['on' => $on])
    {{ logMessage("Running git") }}
    mkdir -p {{ $dirCurrentRelease }}
    git clone --depth 1 -b {{ $gitBranch }} "{{ $gitRepository }}" {{ $dirCurrentRelease }}
    {{ logMessage("Repository has been cloned") }}
@endtask

@task('composer', ['on' => $on])
    {{ logMessage("Running composer") }}
    cd {{ $dirCurrentRelease }}
    composer install --no-interaction --quiet --no-dev --prefer-dist --optimize-autoloader
    {{ logMessage("Composer dependencies have been installed") }}
@endtask

@task('npm_install', ['on' => $on])
    {{ logMessage("Running NPM install") }}

    cd {{ $dirCurrentRelease }}

    npm install --silent --no-progress > /dev/null

    {{ logMessage("Npm dependencies have been installed") }}
@endtask

@task('npm_run_prod', ['on' => $on])
    {{ logMessage("Running NPM run prod") }}

    cd {{ $dirCurrentRelease }}

    npm run prod --silent --no-progress > /dev/null

    {{ logMessage("Deleting node_modules folder") }}
    rm -rf node_modules
@endtask


@task('update_symlinks', ['on' => $on])

    {{ logMessage("Linking storage directory") }}
    rm -rf {{$dirCurrentRelease}}/storage/app;
    cd {{$dirCurrentRelease}};
    ln -nfs {{$dirShared}}/storage/app storage/app;
    php artisan storage:link

    {{ logMessage("Linking current release") }}
    ln -nfs {{ $dirCurrentRelease }} {{ $dirCurrent }};
{{--    chgrp -h {{ $user }} {{ $dirCurrent }}--}}

    {{ logMessage("Linking .env file") }}
    ln -nfs {{ $basePath }}/.env {{ $dirCurrentRelease }}/.env;

    {{ logMessage("Symlink has been set") }}
@endtask

@task('migrate_release', ['on' => $on, 'confirm' => false])
    {{ logMessage("Running migrations") }}

    php {{ $dirCurrentRelease }}/artisan migrate --force

    php {{ $dirCurrentRelease }}/artisan db:seed --class=\\Modules\\Users\\Database\\Seeders\\UsersDatabaseSeeder
@endtask

@task('set_permissions', ['on' => $on])
    # Set dir permissions
    {{ logMessage("Set permissions") }}

    chgrp -h {{ $userGroup }} {{ $dirCurrentRelease }}/.env;

    chown -R {{ $user }}:{{ $userGroup }} {{ $basePath }}

    chown -R {{ $user }}:{{ $userGroup }} {{ $basePath }}/current
    chown -R {{ $user }}:{{ $userGroup }} {{ $dirCurrentRelease }}
    chmod -R ug+rwx {{ $dirCurrentRelease }}

    chgrp -R {{ $userGroup }} {{ $dirShared }}/storage {{ $basePath }}/current/bootstrap/cache
    chmod -R ug+rwx {{ $dirShared }}/storage {{ $basePath }}/current/bootstrap/cache

    {{ logMessage("Permissions have been set") }}
@endtask

@task('cache')
    {{ logMessage("Building cache") }}

    cd {{ $dirCurrentRelease }}

    php artisan route:cache
    php artisan config:cache
    php artisan view:cache

    php artisan clear-compiled --env=production;
    php artisan optimize --env=production;

    {{ logMessage("Cache has been set") }}
@endtask

@task('chmod', ['on' => $on])
    chgrp -R {{ $user }} {{ $dirCurrentRelease }}
    chmod -R ug+rwx {{ $dirCurrentRelease }}

    @foreach($chmods as $file)
        chmod -R 775 {{ $dirCurrentRelease }}/{{ $file }}
        chown -R {{ $user }}:{{ $user }} {{ $dirCurrentRelease }}/{{ $file }}
        echo "Permissions have been set for {{ $file }}"
    @endforeach

    echo "#4 - Permissions has been set"
@endtask


@task('releases_clean')
    purging=$(ls -dt {{$dirReleases}}/* | tail -n +{{$releaseRotate}});
    if [ "$purging" != "" ]; then
        echo "# Purging old releases: $purging;"
        rm -rf $purging;
    else
        echo "# No releases found for purging at this time";
    fi
@endtask

@task('rollback', ['on' => 'prod', 'confirm' => true])
    {{ logMessage("Rolling back...") }}
    cd {{ $dirReleases }}
    ln -nfs {{ $dirReleases }}/$(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1) {{ $baseDir }}/current
    {{ logMessage("Rolled back!") }}

    {{ logMessage("Rebuilding cache") }}
    php {{ $dirCurrentRelease }}/artisan route:cache

    php {{ $dirCurrentRelease }}/artisan config:cache

    php {{ $dirCurrentRelease }}/artisan view:cache
    {{ logMessage("Rebuilding cache completed") }}

    echo "Rolled back to $(find . -maxdepth 1 -name "20*" | sort  | tail -n 2 | head -n1)"
@endtask

@story('deploy', ['on' => 'production'])
git
composer
npm_install
npm_run_prod
update_symlinks
migrate_release
set_permissions
cache
{{--chmod--}}
releases_clean
@endstory

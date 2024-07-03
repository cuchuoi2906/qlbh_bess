@servers(['web' => ['u982566151@31.220.110.240 -p 65002']])

@task('deploy', ['on' => 'web'])
    cd public/affiliate-basaco
    git pull origin dev
    cd database
    vendor/bin/phinx migrate
@endtask
<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/4/18
 * Time: 01:05
 */

namespace AppView;


use App\Models\Users\Users;
use AppView\Commands\BestTeamCommand;
use AppView\Commands\CountTotalPointTeamCommand;
use AppView\Commands\OrderProductCommissionCommand;
use AppView\Commands\TotalTeamPointDayCommand;
use AppView\Commands\UserLevelCommand;
use AppView\Commands\UserTotalReferCommand;
use AppView\Commands\WelcomeCommand;
use AppView\Middlewares\AfterLogRequestMiddleware;
use AppView\Middlewares\AuthBasicMiddleware;
use AppView\Middlewares\BeforeLogRequestMiddleware;
use AppView\Middlewares\UserAuthFromAccessToken;
use AppView\Middlewares\VersionHeaderMiddleware;
use AppView\Repository\PostRepository;
use AppView\Repository\PostRepositoryInterface;
use AppView\Middlewares\UserAuthFromCookie;
use AppView\Middlewares\LoginRequire;
use Firebase\JWT\JWT;
use VatGia\Helpers\ServiceProvider;
use VatGia\Helpers\Facade\Route;

class AppViewServiceProvider extends ServiceProvider
{

    public function register()
    {

        $this->registerBindingRepository();

        $this->registerCommands();
    }

    public function boot()
    {

        $this->loadRoutes();
        $this->userFromAccessToken();
    }

    public function routeFilter()
    {
        Route::filter(UserAuthFromCookie::class, new UserAuthFromCookie);
        Route::filter(LoginRequire::class, new LoginRequire);
        Route::filter(UserAuthFromAccessToken::class, new UserAuthFromAccessToken());
        Route::filter(BeforeLogRequestMiddleware::class, new BeforeLogRequestMiddleware());
        Route::filter(AfterLogRequestMiddleware::class, new AfterLogRequestMiddleware());
        Route::filter(AuthBasicMiddleware::class, new AuthBasicMiddleware());
        Route::filter(VersionHeaderMiddleware::class, new VersionHeaderMiddleware());
    }

    public function loadRoutes()
    {

        $this->routeFilter();

        Route::group([
            'before' => [
                UserAuthFromCookie::class
            ]
        ], function () {
            $this->loadRoutesFrom(base_path('appview/routes/web.php'));
        });
    }

    public function registerBindingRepository()
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
    }

    public function registerCommands()
    {
        $this->commands([
            WelcomeCommand::class,
            UserLevelCommand::class,
            OrderProductCommissionCommand::class,
            UserTotalReferCommand::class,
            CountTotalPointTeamCommand::class,
            TotalTeamPointDayCommand::class,
            BestTeamCommand::class
        ]);
    }

    public function userFromAccessToken()
    {
        $access_token = getValue('access_token', 'str', 'GET', '');

        try {
            $token = JWT::decode($access_token, config('app.jwt_key'), ['HS256']);
        } catch (\Exception $e) {

        }

        if (isset($token)) {
            $user_id = $token->user_id;
            $user_token = Users::findByID($user_id);
        }


        if ($user_token ?? false) {
            $username = $user_token->loginname;
            $password = $user_token->password;

            $_COOKIE['loginname'] = $username;
            $_COOKIE['PHPSESSlD'] = $password;
        }

        $user = new \user();
        app()->singleton('auth', $user);
    }

}
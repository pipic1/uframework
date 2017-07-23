<?php
require __DIR__ . '/../vendor/autoload.php';

use Http\Request;
use Http\JsonResponse;
use Dal\Connection;
use Exception\HttpException;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
    ), $debug);

$dsn = 'mysql:host=127.0.0.1;dbname=uframework;port=32769' ;
$user = 'uframework' ;
$password = 'p4ssw0rd';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$connection = new Connection($dsn, $user, $password, $options);
$statusMapperMysql = new \Model\DataMapper\StatusMapper($connection);
$userMapperMysql = new \Model\DataMapper\UserMapper($connection);
$statusFinderMysql = new \Model\Finder\StatusFinder($connection);
$userFinderMysql = new \Model\Finder\UserFinder($connection);

/**
 * Index
 */
$app->get('/', function () use ($app) {
    return $app->redirect('/statuses');
});

/**
 * Statuses
 */
$app->get('/statuses', function (Request $request) use ($app, $statusFinderMysql) {
    $filter['limit'] = $request->getParameter("limit") ? htmlspecialchars(preg_replace('/\s+/', '', $request->getParameter("limit"))) : null;
    $filter['orderBy'] = $request->getParameter("orderBy") ? $request->getParameter("orderBy") : null;
    $filter['order'] = $request->getParameter("order") ? $request->getParameter("order") : null;

    $statuses = $statusFinderMysql->findAll($filter);

    if ($request->guessBestFormat() == 'application/json') {
        return JsonResponse(json_encode($statuses));
    }

    return $app->render('statuses.php', array('statuses' => $statuses));
});

$app->post('/statuses', function (Request $request) use ($app, $statusMapperMysql) {
    $message = htmlspecialchars(trim($request->getParameter('message')));
    
    if ($message === "") {
        return $app->redirect('/statuses');
    }

    $user = isset($_SESSION['login']) ? $_SESSION['login'] : null;
    if ($user !== null && isset($_SESSION['id'])) {
        $user = new \Model\User($user, null, null, $_SESSION['id']);
    }

    $statusMapperMysql->persist(new \Model\Status($user, $message, new DateTime()));
    
    return $app->redirect('/statuses');
});

/**
 * Status
 */
$app->get('/statuses/(\d+)', function (Request $request, $id) use ($app, $statusFinderMysql) {
    $status = $statusFinderMysql->findOneById($id);

    try {
        if ($status === null) {
            throw new HttpException(404, "Status not found");
        }
    } catch (HttpException $e) {
        return $app->render('errorStatus.php', array('message' => $e->getMessage()));
    }

    if ($request->guessBestFormat() == 'application/json') {
        return JsonResponse(json_encode($status));
    }

    return $app->render('status.php', array('status' => $status));
});

$app->delete('/statuses/(\d+)', function (Request $request, $id) use ($app, $statusMapperMysql) {
    $statusMapperMysql->remove($id);

    return $app->redirect('/statuses');
});

/**
 * Login
 */
$app->get('/login', function (Request $request, $id) use ($app, $userFinderMysql) {
    return $app->render('login.php');
});

$app->post('/login', function (Request $request) use ($app, $userFinderMysql) {
    $login = $request->getParameter('login');
    $password = $request->getParameter('password');

    $user = $userFinderMysql->findOneByLogin($login);

    try {
        if ($user === null) {
            throw new HttpException(403, "Could not find user's login");
        }
        if (!$user->verifyPassword($password)) {
            throw new HttpException(403, "Wrong password for this user");
        }
    } catch (HttpException $e) {
        return $app->render('login.php', array('message' => $e->getMessage()));
    }

    $_SESSION['id'] = $user->getId();
    $_SESSION['login'] = $user->getLogin();
    $_SESSION['is_authenticated'] = true;

    return $app->redirect('/statuses');
});

/**
 * Register
 */
$app->get('/register', function (Request $request) use ($app) {
    return $app->render('register.php');
});

$app->post('/register', function (Request $request) use ($app, $userMapperMysql, $userFinderMysql) {
    $login = $request->getParameter('login');
    $password = $request->getParameter('password');

    $user = $userFinderMysql->findOneByLogin($login);

    try {
        if ($user !== null) {
            throw new HttpException(403, "Login already exists");
        }
    } catch (HttpException $e) {
        return $app->render('register.php', array('message' => $e->getMessage()));
    }

    $userMapperMysql->persist(new \Model\User($login, $password));

    return $app->redirect('/login');
});

/**
 * Logout
 */
$app->get('/logout', function (Request $request) use ($app) {
    session_destroy();
    return $app->redirect('/');
});

/**
 * Firewall
 */
$app->addListener('process.before', function (Request $request) use ($app) {
    if ($request->guessBestFormat() === 'application/json') {
        return;
    }

    session_start();

    $allowed = [
        '/' => [ Request::GET ],
        '/login' => [ Request::GET, Request::POST ],
        '/statuses' => [ Request::GET, Request::POST ],
        '/statuses/(\d+)' => [ Request::GET ],
        '/register' => [ Request::GET, Request::POST ],
    ];

    if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true) {
        return;
    }

    foreach ($allowed as $pattern => $methods) {
        if (preg_match(sprintf('#^%s$#', $pattern), $request->getUri()) && in_array($request->getMethod(), $methods)) {
            return;
        }
    }

    return $app->redirect('/login');
});

return $app;

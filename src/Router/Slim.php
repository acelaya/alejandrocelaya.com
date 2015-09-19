<?php
namespace Acelaya\Website\Router;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Router;
use Zend\Expressive\Exception;
use Zend\Expressive\Router\Route;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Router\RouterInterface;

class Slim implements RouterInterface
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router = null)
    {
        if (null === $router) {
            $router = $this->createRouter();
        }

        $this->router = $router;
    }

    /**
     * Create a default Aura router instance
     *
     * @return Router
     */
    private function createRouter()
    {
        return new Router();
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $slimRoute = new \Slim\Route($route->getPath(), [$this, 'dummyCallable']);
        $slimRoute->setName($route->getName());

        $allowedMethods = $route->getAllowedMethods();
        $slimRoute->via($allowedMethods === Route::HTTP_METHOD_ANY ? 'ANY' : $allowedMethods);

        // Process options
        $options = $route->getOptions();
        if (isset($options['conditions']) && is_array($options['conditions'])) {
            $slimRoute->setConditions($options['conditions']);
        }
        $params = [
            'middleware' => $route->getMiddleware()
        ];
        if (isset($options['defaults']) && is_array($options['defaults'])) {
            $params = array_merge($options['defaults'], $params);
        }
        $slimRoute->setParams($params);

        $this->router->map($slimRoute);
    }

    public function dummyCallable()
    {

    }

    /**
     * @param  Request $request
     * @return RouteResult
     */
    public function match(Request $request)
    {
        $matchedRoutes = $this->router->getMatchedRoutes($request->getMethod(), $request->getUri()->getPath());
        if (count($matchedRoutes) === 0) {
            return RouteResult::fromRouteFailure();
        }

        /** @var \Slim\Route $matchedRoute */
        $matchedRoute = array_shift($matchedRoutes);
        $params = $matchedRoute->getParams();
        $middleware = $params['middleware'];
        unset($params['middleware']);
        return RouteResult::fromRouteMatch(
            $matchedRoute->getName(),
            $middleware,
            $params
        );
    }

    /**
     * Generate a URI from the named route.
     *
     * Takes the named route and any substitutions, and attempts to generate a
     * URI from it.
     *
     * @see https://github.com/auraphp/Aura.Router#generating-a-route-path
     * @see http://framework.zend.com/manual/current/en/modules/zend.mvc.routing.html
     * @param string $name
     * @param array $substitutions
     * @return string
     * @throws Exception\RuntimeException if unable to generate the given URI.
     */
    public function generateUri($name, array $substitutions = [])
    {
        if (! $this->router->hasNamedRoute($name)) {
            throw new Exception\RuntimeException(sprintf(
                'Cannot generate URI based on route "%s"; route not found',
                $name
            ));
        }

        return $this->router->urlFor($name, $substitutions);
    }
}

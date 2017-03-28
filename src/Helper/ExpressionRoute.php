<?php
namespace Helper;

class ExpressionRoute extends ExpressionRouteAbstract
{
    protected static $istance = null;

    //Singleton.
    public static function getIstance()
    {
        if (self::$istance === null) {
            self::$istance = new ExpressionRoute();
        }

        return self::$istance;
    }

    //The method format $expression route string.
    public function formatExpression($expression, $requirements = false)
    {
        //The expression contain wildcard?
        $pregMatchAll = preg_match_all("/{.[^\/]+}/", $expression, $allMatch);

        //If yes ..
        if (!empty($pregMatchAll)) {
            //Are there some requirements for wildcards?
            if (empty($requirements)) {
                //If no, call formatExpressionNoRequirements.
                $expression = $this->formatExpressionNoRequirements($allMatch, $expression);
            } else {
                //If yes, call formatExpressionRequirements.
                $expression = $this->formatExpressionRequirements($allMatch, $requirements, $expression);
            }
        }

        return $expression;
    }

    protected function formatExpressionRequirements(array $allMatch, array $requirements, $expression)
    {
        if (empty($allMatch) || empty($requirements)) {
            return $expression;
        }

        foreach ($allMatch as $match) {
            foreach ($match as $wildcard) {
                $wildcardName = substr($wildcard, 1, strlen($wildcard) -2);

                $expression = array_key_exists($wildcardName, $requirements) ?
                                preg_replace('/' . $wildcard . '/', $requirements[$wildcardName], $expression) :
                                    preg_replace('/' . $wildcard . '/', '(.)+', $expression);
            }
        }

        return $expression;
    }

    protected function formatExpressionNoRequirements(array $allMatch, $expression)
    {
        if (empty($allMatch)) {
            return $expression;
        }

        foreach ($allMatch as $match) {
            foreach ($match as $wildcard) {
                $expression = preg_replace('/' . $wildcard . '/', '(.)+', $expression);
            }
        }

        return $expression;
    }
}

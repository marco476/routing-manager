<?php
namespace Helper;

class ExpressionRoute extends ExpressionRouteAbstract
{
	/**
	 * @var ExpressionRoute
	 */
	protected static $istance = null;

	/**
	 * Singleton
	 *
	 * @return ExpressionRoute
	 */
	public static function getIstance()
	{
		if (self::$istance === null) {
			self::$istance = new ExpressionRoute();
		}

		return self::$istance;
	}

	/**
	 * Format $expression route string.
	 *
	 * @param string $expression
	 * @param boolean $requirements
	 * @return string
	 */
	public function formatExpression($expression, $requirements = false)
	{
		//The expression contain wildcard?
		$pregMatchAll = preg_match_all("/{.[^\/]+}/", $expression, $allMatch);

		//If yes ..
		if (!empty($pregMatchAll)) {
			//Are there some requirements for wildcards?     

			$expression = 
				//If no, call formatExpressionNoRequirements.                   
				empty($requirements) ?
					$this->formatExpressionNoRequirements($allMatch, $expression) :
					//If yes, call formatExpressionRequirements.
						$this->formatExpressionRequirements($allMatch, $requirements, $expression);
		}

		return $expression;
	}

	/**
	 * Format expression with requirements.
	 *
	 * @param array $allMatch
	 * @param array $requirements
	 * @param string $expression
	 * @return string
	 */
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

	/**
	 * Format expression without requirements.
	 *
	 * @param array $allMatch
	 * @param string $expression
	 * @return string
	 */
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

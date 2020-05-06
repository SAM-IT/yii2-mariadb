<?php
declare(strict_types=1);


namespace SamIT\Yii2\MariaDb;

use yii\db\ExpressionInterface;
use yii\db\JsonExpression;
use yii\db\Query;
use yii\helpers\Json;

/**
 * Class JsonExpressionBuilder
 * @package SamIT\Yii2\MariaDb
 */
class JsonExpressionBuilder extends \yii\db\mysql\JsonExpressionBuilder
{
    /**
     * @param JsonExpression $expression
     * @param array $params
     * @return string
     */
    public function build(ExpressionInterface $expression, array &$params = [])
    {
        $value = $expression->getValue();

        if ($value instanceof Query) {
            list ($sql, $params) = $this->queryBuilder->build($value, $params);
            return "($sql)";
        }

        $placeholder = static::PARAM_PREFIX . \count($params);
        $params[$placeholder] = Json::encode($value);
        return $placeholder;
    }
}

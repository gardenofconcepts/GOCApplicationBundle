<?php

namespace GOC\ApplicationBundle\Form\Extension\Core\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a php array and a JSON formated string.
 *
 * @author Bernhard Schussek <bernhard.schussek@symfony.com>
 * @author Florian Eckerstorfer <florian@eckerstorfer.org>
 */
class ArrayToJSONStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms between a normalized format (array) into a JSON formated string.
     *
     * @param  number $value  Normalized value (array)
     *
     * @return number         JSON fomarted value
     *
     * @throws UnexpectedTypeException if the given value is not string
     * @throws TransformationFailedException if the value could not be transformed
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        $value = json_encode($value);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new TransformationFailedException($this->getLastError(json_last_error()));
        }

        // replace the UTF-8 non break spaces
        return $value;
    }

    /**
     * Transforms between JSON formated string into a normalized format (array).
     *
     * @param  number $value  JSON fomarted value
     *
     * @return number         Normalized value (array)
     *
     * @throws UnexpectedTypeException if the given value is not a string
     * @throws TransformationFailedException if the value could not be transformed
     */
    public function reverseTransform($value)
    {
        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if ('' === $value) {
            return null;
        }

        $value = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new TransformationFailedException($this->getLastError(json_last_error()));
        }

        return $value;
    }

    protected function getLastError($error)
    {
        switch ($error) {
            case JSON_ERROR_NONE:
                return 'No errors';
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GOC\ApplicationBundle\Form\Extension\Core\Type;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilder;

use GOC\ApplicationBundle\Form\Extension\Core\DataTransformer\ArrayToJSONStringTransformer;

class JSONType extends HiddenType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->appendClientTransformer(new ArrayToJSONStringTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'json';
    }
}

<?php

/*
 * This file is part of the Ivory Base64 File package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Base64FileBundle\Form\Extension;

use Ivory\Base64FileBundle\Form\DataTransformer\Base64FileTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Base64FileExtension extends AbstractTypeExtension
{
    /**
     * @var bool
     */
    private $base64;

    /**
     * @param bool $base64
     */
    public function __construct($base64)
    {
        $this->base64 = $base64;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['base64']) {
            $builder->addViewTransformer(new Base64FileTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'base64'     => $this->base64,
            'data_class' => function (Options $options, $value) {
                return !$options['base64'] ? $value : null;
            },
        ]);

        if (method_exists($resolver, 'setDefault')) {
            $resolver->setAllowedTypes('base64', 'bool');
        } else {
            $resolver->setAllowedTypes(array('base64' => 'bool'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'Symfony\Component\Form\Extension\Core\Type\FileType';
        }

        return 'file';
    }
}

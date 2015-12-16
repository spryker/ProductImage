<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\IdeAutoCompletion;

use Symfony\Component\OptionsResolver\OptionsResolver;

class IdeBundleAutoCompletionGenerator extends AbstractIdeAutoCompletionGenerator
{

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_INTERFACE_NAME => 'BundleAutoCompletion',
        ]);
    }

    /**
     * @param null $output
     *
     * @return void
     */
    public function create($output = null)
    {
        $fileContent = $this->getBaseFile($this->options[self::OPTION_KEY_NAMESPACE]);
        $fileContent .= $this->generateInterfaces();

        $this->saveFileContent($fileContent);
    }

    /**
     * @return string
     */
    private function generateInterfaces()
    {
        $bundles = $this->getBundles();
        $interfaces = '';
        foreach ($bundles as $bundle) {
            $methodTags = $this->getMethodTagsByBundle($bundle);
            if (count($methodTags) > 0) {
                $interface = '/**' . PHP_EOL;
                $interface .= implode(PHP_EOL, $methodTags) . PHP_EOL;
                $interface .= ' */' . PHP_EOL;
                $interface .= 'interface ' . $bundle . PHP_EOL . '{}' . PHP_EOL;

                $interfaces .= $interface . PHP_EOL;
            }
        }

        return $interfaces;
    }

}